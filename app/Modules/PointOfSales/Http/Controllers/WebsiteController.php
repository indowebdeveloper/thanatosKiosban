<?php

namespace Thanatos\Modules\PointOfSales\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\PointOfSales\Models\Website as Website;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;
use Image;

class WebsiteController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:website-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $cT = Website::all();  
    return Datatables::of($cT)
    ->addColumn('action', function ($cT) {
          $a = '<a href="/'.config('app.admin_url').'#!/websites/edit/'.$cT->id.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
          $a .= '<a href="javascript:void()" data-id="'.$cT->id.'" data-name="'.$cT->name.'" class="btn btn-sm btn-danger rm-website" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
          return $a;
    })
    ->escapeColumns(['action'])
    ->make(true);
  }

  /**
   * Display all company Type.
   *
   * @return Response
   */
  public function all()
  {
    $cT = Website::all();
    return json_encode([
        'websites' => $cT
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
      // condition for personal and corporation
      
        $mainvalidate = Validator::make($r->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);
        if(!$mainvalidate->fails()){
            // personal
            $cT = Website::create([
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'website' => $r->website,
                'slogan' => $r->slogan,
                'mail_inboxes' => json_encode($r->mail_inboxes),
                'sales_inboxes' => json_encode($r->sales_inboxes),
                'finance_inboxes' => json_encode($r->finance_inboxes),
                'purchasing_inboxes' => json_encode($r->purchasing_inboxes),
                'address' => $r->address
            ]);
            // now the image
            $img = '/assets/images/websites/'.$cT->id.'.png';
            if(!isset($r->logo['isPath'])){
              Image::make($r->logo["src"])->save(public_path($img));
            }
            Website::find($cT->id)->update([
                'logo' => $img
            ]);
            // broadcast notification to anyone who can manage a customer
            BroadcastNotification([
                'website-management'
            ]," Has created a new website named : ".$r->name);
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
    $cT = Website::where('id',$id)->first();
    if($cT){
      return json_encode([
        'success' => true,
        'websites' => $cT
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
    $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
    ]);
    if(!$mainvalidate->fails()){
        $img = '/assets/images/websites/'.$r->id.'.png';
        if(!isset($r->logo['isPath'])){
            Image::make($r->logo["src"])->save(public_path($img));
        }
        $cT = Website::find($r->id)->update([
            'name' => $r->name,
            'email' => $r->email,
            'phone' => $r->phone,
            'website' => $r->website,
            'slogan' => $r->slogan,
            'mail_inboxes' => json_encode($r->mail_inboxes),
            'sales_inboxes' => json_encode($r->sales_inboxes),
            'finance_inboxes' => json_encode($r->finance_inboxes),
            'purchasing_inboxes' => json_encode($r->purchasing_inboxes),
            'address' => $r->address
        ]); 
       BroadcastNotification([
        'website-management'
        ]," Updating website ( ID : ".$r->id." ) ");
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
       // broadcast notification to anyone who can manage a customer
       BroadcastNotification([
        'website-management'
        ]," Delete a website ( ID : ".$r->id." ) ");
      $del = Website::where('id',$r->id)->delete();
      return json_encode([
        "success" => true,
        "msg" => "The company has been successfully removed"
      ]);
   
    
  }

}

?>
