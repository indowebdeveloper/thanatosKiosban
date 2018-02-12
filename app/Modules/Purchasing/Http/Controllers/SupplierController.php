<?php

namespace Thanatos\Modules\Purchasing\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\Purchasing\Models\Suppliers as Supplier;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;

class SupplierController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:supplier-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $cT = Supplier::with('geoProvince','geoCity','geoCountry')
    ->when(($r->country!=""), function ($query) use ($r) {
        return $query->where('country', $r->country);
      })
      ->when(($r->province!=""), function ($query) use ($r) {
        return $query->where('province', $r->province);
      })
      ->when(($r->city!=""), function ($query) use ($r) {
        return $query->where('city', $r->city);
      })
      ->when(($r->speciality!=""), function ($query) use ($r) {
        return $query->whereRaw('FIND_IN_SET("'.$r->speciality.'",speciality)');
      })
      ->get();  
    return Datatables::of($cT)
    ->addColumn('action', function ($cT) {
          $a = '<a href="/'.config('app.admin_url').'#!/suppliers/edit/'.$cT->supplier_code.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
          $a .= '<a href="javascript:void()" data-id="'.$cT->supplier_code.'" data-name="'.$cT->name.'" class="btn btn-sm btn-danger rm-supplier" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
          return $a;
    })
    ->editColumn('speciality', function ($cT) {
        $arr = explode(",",$cT->speciality);
        $htm = "";
        if(is_array($arr)){
            foreach($arr as $k=>$v){
                $htm .= "<span class='tag tag-default tag-success'>".$v."</span>";
            }
        }
        return $htm;
    })
    ->escapeColumns(['action'])
    ->make(true);
  }

 

  /**
   * create a new supplier
   *
   * @return Response
   */
  public function create(Request $r)
  {
      $create = Supplier::create([
        'name' => $r->name,
        'email' => $r->email,
        'phone' => $r->phone,
        'website' => $r->website,
        'operational_hour' => $r->operational_hour,
        'speciality' => implode(",",$r->speciality),
        'address' => $r->address,
        'country' => $r->country,
        'province' => $r->province,
        'city' => $r->city,
        'contact_person' => $r->contact_person,
        'account_number' => $r->account_number,
        'account_holder' => $r->account_holder,
        'bank' => $r->bank,
        'payment_terms' => $r->payment_terms,
        'supplier_code' => uniqid(rand()),
        'longitude' => $r->longitude,
        'latitude' => $r->latitude,
        'this_week_orders' => 0
      ]);
      if($create){
            // broadcast
            BroadcastNotification([
                'supplier-management'
            ]," Has created a new supplier named : ".$r->name);
            
            return json_encode([
                "success" => true,
                "msg" => "Your data has been save"
            ]);
       }else{
            return json_encode([
                "success" => false,
                "msg" => "Your data couldnt be save"
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
    $cT = Supplier::where('supplier_code',$id)->first();
    if($cT){
      $cT->speciality = explode(",",$cT->speciality);
      return json_encode([
        'success' => true,
        'supplier' => $cT
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
    $update = Supplier::where('supplier_code',$r->supplier_code)->update([
        'name' => $r->name,
        'email' => $r->email,
        'phone' => $r->phone,
        'website' => $r->website,
        'operational_hour' => $r->operational_hour,
        'speciality' => implode(",",$r->speciality),
        'address' => $r->address,
        'country' => $r->country,
        'province' => $r->province,
        'city' => $r->city,
        'contact_person' => $r->contact_person,
        'account_number' => $r->account_number,
        'account_holder' => $r->account_holder,
        'bank' => $r->bank,
        'payment_terms' => $r->payment_terms,
        'longitude' => $r->longitude,
        'latitude' => $r->latitude
      ]);
      if($update){
            // broadcast
            BroadcastNotification([
                'supplier-management'
            ]," Has update a supplier named : ".$r->name);
            
            return json_encode([
                "success" => true,
                "msg" => "Your data has been save"
            ]);
       }else{
            return json_encode([
                "success" => false,
                "msg" => "Your data couldnt be save"
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
        'supplier-management'
        ]," Delete a Supplier ( ID : ".$r->id." ) ");
      $del = Supplier::where('supplier_code',$r->id)->delete();
      return json_encode([
        "success" => true,
        "msg" => "The supplier has been successfully removed"
      ]);
   
    
  }

}

?>
