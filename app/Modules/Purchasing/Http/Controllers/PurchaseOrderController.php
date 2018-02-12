<?php

namespace Thanatos\Modules\Purchasing\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\PointOfSales\Models\Order as Order;
use Thanatos\Modules\PointOfSales\Models\OrderItems as PR;
use Thanatos\Modules\PointOfSales\Models\OrderCustomer as OrderCustomer;
use Thanatos\Modules\Purchasing\Models\PurchaseOrders as PO;
use Thanatos\Modules\Purchasing\Models\Suppliers as Supplier;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;
use Thanatos\Mail\PurchaseOrderCreatedNotifyAdmin;
use Thanatos\Mail\PurchaseOrderCreatedNotifySupplier;
use DB;
use Illuminate\Support\Facades\Mail;
use Auth;

class PurchaseOrderController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:purchase-order-management');
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

  /**
   * Create Purchase Order
   */
  public function create(Request $r,$id)
  {
    $po = json_decode($r->data);
    $totalBought = 0;
    // get the PR
    $pr_id = $po[0]->pr_id; // using one sample
    $pr = PR::where('id',$po[0]->pr_id)->with('geoCountry','geoProvince','geoCity')->first();
    // get the Order
    $order = Order::where('id',$po[0]->order_id)->with('websiteData')->first();
    // get the website owner of this order
    $website = $order->websiteData->toArray();
    // process the Orders
    foreach($po as $k=>$v){
        // manipulate status, if the real price are bigger than capital price
        if($v->capital_price<$v->real_price){
            $po[$k]->status = 'waiting_approval';
        }
        // changes it into array
        $po[$k] = (array) $v;
        // unset the "supplier" index because we dont need it to be inserted
        unset($po[$k]["supplier"]);
        // set shipped date to null
        $po[$k]['shipped_date'] = null;
        // Now we create the Model
        $this_po = PO::create($po[$k]);
        // Broadcast dude
        BroadcastNotification([
            'purchasing-management'
        ]," Has created a new PO : ".$order->order_number.'#'.$this_po->id);
        // oops dont forget to increment the this week orders
        Supplier::where('id', $po[$k]['supplier_id'])
        ->update(['this_week_orders'=>DB::raw('this_week_orders+'.$po[$k]['qty'])]);
        // If the status are not waiting approval, then send an email
        if($po[$k]['status']=='pending'){
            // prepare statement for sending email to supplier
            $supplier = Supplier::find($po[$k]['supplier_id'])->first();
            // prepare the data for the email
            $data = [
                'website' => $website,
                'po' => $this_po->toArray(),
                'supplier' => $supplier->toArray(),
                'order' => $order->toArray(),
                'pr' => $pr->toArray()
            ];
            // now send to admins ( vendor mail inboxes )
            $purchasing = json_decode($website['purchasing_inboxes']);
            $master = json_decode($website['mail_inboxes']);
            
            Mail::to($purchasing)->cc($master)->queue(new PurchaseOrderCreatedNotifyAdmin($data));
            // now send to supplier
            Mail::to($supplier->email)->queue(new PurchaseOrderCreatedNotifySupplier($data));
        }else{
            // broadcast to purchase order approval
            BroadcastNotification([
                'purchase-order-approval'
            ]," Has created a new PO which requires Approval : ".$order->order_number.'#'.$this_po->id);
        }
    }
    
    // also update the PR Assignment
    PR::where('id',$pr_id)->update([
        'assignment' => Auth::user()->id
    ]);
    // That's it  
    return json_encode([
        "success" => true,
        "msg" => "Successfuly Create Purchase Order"
    ]);
        
  }
}
?>
