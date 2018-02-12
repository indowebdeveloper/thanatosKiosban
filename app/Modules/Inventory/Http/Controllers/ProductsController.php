<?php 

namespace Thanatos\Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\Inventory\Models\Products as Products;
use Thanatos\Modules\Inventory\Models\ProductCategory as Category;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;
use Image;

class ProductsController extends Controller 
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $products = Products::select(['id','name','product_code','category_id','price','capital_price','sale_price','qty','description','country','province','city','image'])
    ->when(($r->category!=""), function ($query) use ($r) {
      return $query->where('category_id', $r->category);
    })
    ->when(($r->country!=""), function ($query) use ($r) {
      return $query->where('country', $r->country);
    })
    ->when(($r->province!=""), function ($query) use ($r) {
      return $query->where('province', $r->province);
    })
    ->when(($r->city!=""), function ($query) use ($r) {
      return $query->where('city', $r->city);
    });
    return Datatables::of($products)
    ->addColumn('action', function ($p) {
          if(hasPermission('delete-product') || hasPermission('edit-product')){
            $a = "";
            if(hasPermission('edit-product')){
              $a .= '<a href="/'.config('app.admin_url').'#!/products/edit/'.$p->id.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
            }
            if(hasPermission('delete-product')){
              $a .= '<a href="javascript:void()" data-id="'.$p->id.'" data-name="'.$p->name.'" class="btn btn-sm btn-danger rm-product" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
            }
          }else{
            $a = '-';
          }
          return $a;
    })
    ->editColumn('category_id',function($p){
         return Category::where('id',$p->category_id)->first()->name;
    })
    ->editColumn('city',function($p){
      return geoData($p->city)->name;
    })
    ->editColumn('province',function($p){
      return geoData($p->province)->name;
    })
    ->editColumn('country',function($p){
      return geoData($p->country)->name;
    })
    ->editColumn('price',function($p){
      return toCurrency($p->price);
    })
    ->editColumn('capital_price',function($p){
      return toCurrency(($p->capital_price!=""?$p->capital_price:0));
    })
    ->editColumn('sale_price',function($p){
      return toCurrency(($p->sale_price!=""?$p->sale_price:0));
    })
    ->editColumn('qty',function($p){
      return ($p->qty!=""?$p->qty:'-');
    })
    ->editColumn('image',function($p){
         return "<img src='".$p->image."' style='max-width:200px;'>";
    })
    ->escapeColumns(['action'])
    ->make(true);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(Request $r)
  {
      // prepare the validation
      $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255',
        'price' => 'required',
        'country' => 'required',
        'province' => 'required',
        'city' => 'required',
        'image' => 'required',
        'category_id' => 'required'
      ]);
      if(!$mainvalidate->fails()){
        // get the category CODE for product code
        $cat = Category::where('id',$r->category_id)->first();
        $lastProductSameCat = Products::select('product_code')->where('product_code', 'like', $cat->code.'%')
        ->orderBy('id', 'desc')
        ->first();
        // extract the index number
        if(count($lastProductSameCat)>0){
          $lastSlug = explode("-",$lastProductSameCat->product_code);
          $lastSlug = end($lastSlug);
          $count = $lastSlug+1;
        }else{
          $count = (int)1;
        }
        // sooo the product code are
        $code = $cat->code."-".$count;
        // GREAT!! now let's process the image
        $img = '/assets/images/products/'.$code.'.png';
        if(!isset($r->image['isPath'])){
          Image::make($r->image["src"])->save(public_path($img));
        }
        // now we can create the real goddamn product
        $product = Products::create([
            'name' => $r->name,
            'description' => $r->description,
            'price' => $r->price,
            'capital_price' => $r->capital_price,
            'sale_price' => $r->sale_price,
            'qty' => $r->qty,
            'image' => $img,
            'country' => $r->country,
            'province' => $r->province,
            'city' => $r->city,
            'category_id' => $r->category_id,
            'product_code' => $code
        ]);
        
        // return response
        return json_encode([
          "success" => true,
          "msg" => "Your data has been save"
        ]);
      }else{
        // return response
        return json_encode([
          "success" => false,
          "msg" => $mainvalidate->errors()->all()
        ]);
      } 
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function get($id)
  {
    $p = Products::where('id',$id)->first();
    if($p){
      return json_encode([
        'success' => true,
        'product' => $p
      ]);
    }else{
      return json_encode([
        'success' => false
      ]);
    }
  }

  /**
   * updating the data
   *
   * @param  object  $r
   * @return Response
   */
  public function update(Request $r)
  {
    
    $mainvalidate = Validator::make($r->all(), [
        'id' => 'required|exists:products',
        'name' => 'required|max:255',
        'price' => 'required',
        'country' => 'required',
        'province' => 'required',
        'city' => 'required',
        'image' => 'required'
    ]);
    if(!$mainvalidate->fails()){
      $img = '/assets/images/products/'.$r->product_code.'.png';
      if(!isset($r->image['isPath'])){
        Image::make($r->image["src"])->save(public_path($img));
      }
      $update = Products::where('id',$r->id)
      ->update([
        'name' => $r->name,
        'description' => $r->description,
        'price' => $r->price,
        'capital_price' => $r->capital_price,
        'sale_price' => $r->sale_price,
        'qty' => $r->qty,
        'image' => $img,
        'country' => $r->country,
        'province' => $r->province,
        'city' => $r->city
      ]);
      // return response
      return json_encode([
        "success" => true,
        "msg" => 'Your data has been updated'
      ]);
    }else{
      // return response
      return json_encode([
        "success" => false,
        "msg" => $mainvalidate->errors()->all()
      ]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $r)
  {
    $cat = Products::where('id',$r->id)->delete();
    return json_encode([
      "success" => true
    ]);
  }
  
  /**
   * Search functionality
   *
   * @param Request $r contain the request
   * @return Response
   */
  public function search(Request $r){
    $products = Products::search($r->s)->paginate(10);
    //$products->categories = $products->category;
    return json_encode($products);
  }
}

?>