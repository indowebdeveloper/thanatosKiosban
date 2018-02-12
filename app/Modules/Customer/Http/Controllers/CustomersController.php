<?php

namespace Thanatos\Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\Customer\Models\CompanyType as CompanyType;
use Thanatos\Modules\Customer\Models\Customers as Customers;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;

class CustomersController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:customer-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $cT = Customers::select(['id','name','email','phone','type','points','company_type','company_name','company_email','company_phone','company_address'])
    ->when(($r->type!=""), function ($query) use ($r) {
        return $query->where('type', $r->type);
    })
    ->when(($r->company_type!=""), function ($query) use ($r) {
        return $query->where('company_type', $r->company_type);
    });  
    return Datatables::of($cT)
    ->addColumn('action', function ($cT) {
          $a = '<a href="/'.config('app.admin_url').'#!/customers/edit/'.$cT->id.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
          $a .= '<a href="javascript:void()" data-id="'.$cT->id.'" data-name="'.$cT->name.'" class="btn btn-sm btn-danger rm-customer" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
          return $a;
    })
    ->editColumn('type',function($cT){
        return ($cT->type==0?'Personal':'Corporation');
    })
    ->editColumn('company_type',function($cT){
        return ($cT->company_type!=""?CompanyType::where('id',$cT->company_type)->first()->name:' - ');
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
    $cT = Customers::all();
    return json_encode([
        'customers' => $cT
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
      if($r->type==0){
        $mainvalidate = Validator::make($r->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:customer',
            'phone' => 'required|unique:customer'
        ]);    
      }else{
        $mainvalidate = Validator::make($r->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:customer',
            'phone' => 'required|unique:customer',
            'company_name' => 'required|max:255',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required'
        ]);
      }
      if(!$mainvalidate->fails()){
        if($r->type==0){
            // personal
            $cT = Customers::create([
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'type' => $r->type,
                'points' => 0
            ]);
        }else{
            // corporation
            $cT = Customers::create([
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'type' => $r->type,
                'company_type' => $r->company_type,
                'company_name' => $r->company_name,
                'company_address' => $r->company_address,
                'company_email' => $r->company_email,
                'company_phone' => $r->company_phone,
                'points' => 0
            ]);
        }
        // broadcast notification to anyone who can manage a customer
        BroadcastNotification([
            'customer-management'
        ]," Has created a new customer");
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
    $cT = Customers::where('id',$id)->first();
    if($cT){
      return json_encode([
        'success' => true,
        'customers' => $cT
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
    if($r->type==0){
        $mainvalidate = Validator::make($r->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:customer,email,'.$r->id,
            'phone' => 'required|unique:customer,phone,'.$r->id
        ]);
      }else{
        $mainvalidate = Validator::make($r->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:customer,email,'.$r->id,
            'phone' => 'required|unique:customer,phone,'.$r->id,
            'company_name' => 'required|max:255',
            'company_email' => 'required|max:255',
            'company_phone' => 'required'
        ]);
      }
    if(!$mainvalidate->fails()){
        if($r->type==0){
            $update = Customers::where('id',$r->id)
            ->update([
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'type' => $r->type,
                // now we reset the other to become empty or null
                'company_type' => null,
                'company_name' => null,
                'company_address' => null,
                'company_email' => null,
                'company_phone' => null
                ]);
        }else{
            $update = Customers::where('id',$r->id)
            ->update([
                'name' => $r->name,
                'email' => $r->email,
                'phone' => $r->phone,
                'type' => $r->type,
                'company_type' => $r->company_type,
                'company_name' => $r->company_name,
                'company_address' => $r->company_address,
                'company_email' => $r->company_email,
                'company_phone' => $r->company_phone
            ]);
        }
       // broadcast notification to anyone who can manage a customer
       BroadcastNotification([
        'customer-management'
        ]," Updating Customer ( ID : ".$r->id." ) ");
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
        'customer-management'
        ]," Delete a Customer ( ID : ".$r->id." ) ");
      $del = Customers::where('id',$r->id)->delete();
      return json_encode([
        "success" => true,
        "msg" => "The company has been successfully removed"
      ]);
   
    
  }

}

?>
