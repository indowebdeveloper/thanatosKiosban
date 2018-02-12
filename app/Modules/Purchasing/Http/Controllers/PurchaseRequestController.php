<?php

namespace Thanatos\Modules\Purchasing\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\PointOfSales\Models\Order as Order;
use Thanatos\Modules\PointOfSales\Models\OrderItems as OrderItems;
use Thanatos\Modules\PointOfSales\Models\OrderCustomer as OrderCustomer;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;

class PurchaseRequestController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:purchase-request-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $cT = OrderItems::with('geoProvince','geoCity','geoCountry','order','order.websiteData','order.customerData','assignedUser')
    ->whereRaw('qty > bought')
    ->whereHas('order',function($q){
        $q->where('status','approved');
    })
    ->when(($r->country!=""), function ($query) use ($r) {
        return $query->where('country', $r->country);
      })
    ->when(($r->province!=""), function ($query) use ($r) {
        return $query->where('province', $r->province);
      })
    ->when(($r->city!=""), function ($query) use ($r) {
        return $query->where('city', $r->city);
    })
    ->when(($r->status!=""), function ($query) use ($r) {
        if($r->status=='Assigned'){
            return $query->whereNotNull('assignment');
        }else{
            return $query->whereNull('assignment');
        }
        
    })
    ->get();  
    return Datatables::of($cT)
    ->addColumn('action', function ($cT) {
        if($cT->assignment<1){            
            $a = '<a href="/'.config('app.admin_url').'#!/purchase-order/create/'.$cT->id.'"  class="btn btn-sm btn-primary"><i class="icon-android-cart"></i></a>';
        }else{
            $a = " - ";
        }
        return $a;
    })
    ->addColumn('status', function ($cT) {
        if($cT->assignment!=null){            
            $a = '<span class="tag tag-info">Assigned</span>';
        }else{
            $a = '<span class="tag tag-warning">Unassigned</span>';
        }
        return $a;
    })
    ->editColumn('assignment', function ($cT) {
        if(empty($cT->assignedUser)){            
            $a = ' - ';
        }else{
            $a = $cT->assignedUser->name;
        }
        return $a;
    })
    ->editColumn('image', function ($cT) {
        $a = '<img src="'.$cT->image.'" width="200">';
        return $a;
    })
    ->rawColumns(['image','status','action'])
    ->make(true);
  }

    public function get($id){
        $pr = OrderItems::with('geoProvince','geoCity','geoCountry','order','order.websiteData','order.customerData','assignedUser')
        ->where('id',$id)
        ->first();
        return json_encode([
            'pr' => $pr,
            'success' => true
        ]);
    }
}
?>
