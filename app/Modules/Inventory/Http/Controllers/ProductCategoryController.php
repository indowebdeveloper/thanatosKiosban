<?php

namespace Thanatos\Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\Inventory\Models\ProductCategory as ProductCategory;
use Thanatos\Modules\Inventory\Models\Products as Products;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;

class ProductCategoryController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:manage-product-category');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $request)
  {
    $categories = ProductCategory::select(['id','code','name','description','created_at','updated_at']);
    return Datatables::of($categories)
    ->addColumn('action', function ($categories) {
          $a = '<a href="/'.config('app.admin_url').'#!/product-categories/edit/'.$categories->id.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
          $a .= '<a href="javascript:void()" data-id="'.$categories->id.'" data-name="'.$categories->name.'" class="btn btn-sm btn-danger rm-category" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
          return $a;
    })
    ->editColumn('created_at',function($categories){
         return date('F d Y', strtotime($categories->created_at));
    })
    ->editColumn('updated_at',function($categories){
         return date('F d Y', strtotime($categories->updated_at));
    })
    ->escapeColumns(['action'])
    ->make(false);
  }

  /**
   * Display all categories.
   *
   * @return Response
   */
  public function all()
  {
    $categories = ProductCategory::all();
    return json_encode([
        'categories' => $categories
    ]);
  }

  /**
   * create a new category.
   *
   * @return Response
   */
  public function create(Request $r)
  {
      // prepare the validation
      $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255',
        'code' => 'required|unique:product_category|max:255',
        'description' => 'required'
      ]);
      if(!$mainvalidate->fails()){
        $cat = new ProductCategory();
        $cat->name = $r->name;
        $cat->code = $r->code;
        $cat->description = $r->description;
        $cat->save();
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
    $cat = ProductCategory::where('id',$id)->first();
    if($cat){
      return json_encode([
        'success' => true,
        'category' => $cat
      ]);
    }else{
      return json_encode([
        'success' => false
      ]);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $r)
  {
    if($r->code!=$r->old_code){
      // means the code is updated, so also check if the code has been used or not as well
      $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255',
        'code' => 'required|unique:product_category|max:255',
        'description' => 'required',
        'id' => 'required|exists:product_category'
      ]);  
    }else{
      $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255',
        'description' => 'required',
        'id' => 'required|exists:product_category'
      ]);
    }
    if(!$mainvalidate->fails()){
      $update = ProductCategory::where('id',$r->id)
      ->update([
        'name' => $r->name,
        'code' => $r->code,
        'description' => $r->description
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
    // find if the category has been used or not
    $count =  Products::where('category_id',$r->id)->count();
    if($count>0){
      return json_encode([
        "success" => false,
        "msg" => "The Category has being used, you cant remove it"
      ]);
    }else{
      $cat = ProductCategory::where('id',$r->id)->delete();
      return json_encode([
        "success" => true,
        "msg" => "The category has been successfully removed"
      ]);
    } 
    
  }

}

?>
