<?php

namespace Thanatos\Modules\PointOfSales\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\PointOfSales\Models\Enquiries as Enquiries;
use Thanatos\Modules\PointOfSales\Models\Order as Order;
use Thanatos\Modules\PointOfSales\Models\OrderItem as OrderItem;
use Thanatos\Modules\PointOfSales\Models\OrderCustomer as OrderCustomer;
use Thanatos\Modules\PointOfSales\Models\Website as Website;
use Thanatos\Modules\PointOfSales\Models\OrderPayments as OrderPayments;
use Thanatos\Modules\Customer\Models\Customers as Customers;
use DataTables as Datatables;
use Validator;
use Auth;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;
use MongoDB\BSON\UTCDateTime as MongoDate;
use Carbon\Carbon;
use Igaster\LaravelCities\Geo;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
use Thanatos\Mail\OrderCreatedNotifyAdmin;
use Thanatos\Mail\OrderCreatedNotifyCustomer;
use Thanatos\Mail\OrderUpdatedNotifyAdmin;
use Thanatos\Mail\OrderUpdatedNotifyCustomer;
use PDF;

class OrderController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:order-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $eQ = Order::with('customerData')->when(($r->date_from!="" && $r->date_to!=""), function ($query) use ($r) {
        return $query->whereBetween('created_at', [$r->date_from." 00:00:00",$r->date_to.' 23:59:59']);
    })
    ->when(($r->status!=""), function ($query) use ($r) {
        return $query->where('status', $r->status);
    })
    ->when(($r->payment_status!=""), function ($query) use ($r) {
        return $query->where('payment_status', $r->payment_status);
    })
    ->when(($r->user!=""), function ($query) use ($r) {
        return $query->where('owner', (int)$r->user);
    })->get();  
    return Datatables::of($eQ)
    ->addColumn('action', function ($cT) {
          $a = "";
          if($cT->status=='unapproved'){
            $a .= '<a href="/'.config('app.admin_url').'#!/orders/edit/'.$cT->order_number.'"  class="btn btn-sm btn-primary" title="Edit Order"><i class="icon-edit"></i></a>';
          }
          if($cT->payment_status=='unpaid'){
            $a .= '<a href="/'.config('app.admin_url').'#!/orders/add-payment/'.$cT->order_number.'"  style="margin-left:10px;" class="btn btn-sm btn-success" title="Add Payment"><i class="icon-android-open"></i></a>';
          }
          $a .= '<a href="'.config('app.admin_url').'#!/orders/view/'.$cT->order_number.'" style="margin-left:10px;" class="btn btn-sm btn-default" title="View Order"><i class="icon-eye"></i></a>';
          return $a;
    })
    ->editColumn('owner',function($d){
            return \Thanatos\User::where('id',$d->owner)->first()->name;
    })
    ->editColumn('payment_status',function($d){
        switch($d->payment_status){
            case "unpaid":
            return "<span class='tag tag-warning'>UNPAID</span>";
            break;
            case "paid":
            return "<span class='tag tag-success'>PAID</span>";
            break;
        }
    })
    ->editColumn('status',function($d){
        switch($d->status){
            case "unapproved":
            return "<span class='tag tag-warning'>UNAPPROVED</span>";
            break;
            case "cancelled":
            return "<span class='tag tag-default'>CANCELLED</span>";
            break;
            case "approved":
            return "<span class='tag tag-success'>APPROVED</span>";
            break;
        }
    })
    ->editColumn('total',function($d){
        return toCurrency($d->total);
    })
    ->editColumn('created_at',function($d){
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $d->created_at)->format('F j, Y H:i');
    })
    ->escapeColumns(['action'])
    ->make(true);
  }

  /**
   * Display all enquiries
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
   * create a new Order.
   *
   * @return Response
   */
  public function create(Request $r)
  {
    $obj = json_decode($r->data);
    /** 
     * Check the customer, if doesnt exist, create it
     */
    $cD = $obj->customerData;
    if(isset($cD->id)){
        // means customer exists
        $customer_id = $cD->id;
    }else{
        // means doesnt exists
        if($cD->type==0){
            // personal
            $cT = Customers::create([
                'name' => $cD->name,
                'email' => $cD->email,
                'phone' => $cD->phone,
                'type' => $cD->type,
                'points' => 0
            ]);
        }else{
            // corporation
            $cT = Customers::create([
                'name' => $cD->name,
                'email' => $cD->email,
                'phone' => $cD->phone,
                'type' => $cD->type,
                'company_type' => $cD->company_type,
                'company_name' => $cD->company_name,
                'company_address' => $cD->company_address,
                'company_email' => $cD->company_email,
                'company_phone' => $cD->company_phone,
                'points' => 0
            ]);
        }
        $customer_id = $cT->id;
    }
    /** 
     * first we save the main object, which is order
     * order number pattern is : (last_order_index+1)(YYMMDD)(inquiry_index)
     **/
    $order_number = DB::table('order')->max('id') + 1;
    $dt = Carbon::now()->format('ymd');
    $order_number = $order_number.$dt.$obj->id;
    // unique code for payment 
    $un = randNum(3);
    // modify total
    $total = substr($obj->total, 0, -3) . $un;
    $order = Order::create([
        'order_number' => $order_number,
        'customer_id' => $customer_id,
        'owner' => $obj->owner,
        'website' => $obj->website,
        'status' => 'unapproved',
        'payment_status' => 'unpaid',
        'total' => $total,
        'real_invoice' => $obj->real_invoice,
        'inquiry_id' => $obj->id,
        'unique_code' => $un
    ]);
    // now we create the relation, first, we store the customer data
    $customerData = new OrderCustomer([
        'name' => $cD->name,
        'email' => $cD->email,
        'phone' => $cD->phone,
        'type' => $cD->type,
        'company_type' => (isset($cD->company_type)?$cD->company_type:null),
        'company_name' => (isset($cD->company_name)?$cD->company_name:null),
        'company_email' => (isset($cD->company_email)?$cD->company_email:null),
        'company_phone' => (isset($cD->company_phone)?$cD->company_phone:null),
        'company_address' => (isset($cD->company_address)?$cD->company_address:null)
    ]);
    $order->customerData()->save($customerData);
    // next we save the items
    $items = [];
    foreach($obj->cart as $cart){
        $items[] = [
            'product_id' => $cart->id,
            'name' => $cart->name,
            'price' => $cart->price,
            'qty' => $cart->qty,
            'image' => $cart->image,
            'product_code' => $cart->product_code,
            'subtotal' => $cart->subtotal,
            'shipping_cost' => $cart->shipping_cost,
            'shipping_expedition' => $cart->shipping_expedition,
            'shipping_address' => $cart->shipping_address,
            'sender_name' => $cart->sender_name,
            'greetings' => $cart->greetings,
            'receiver_name' => $cart->receiver_name,
            'receiver_phone' => $cart->receiver_phone,
            'notes' => (isset($cart->notes)?$cart->notes:''),
            'city' => $cart->city,
            'country' => $cart->country,
            'province' => $cart->province,
            'date_time' => $cart->date_time,
            'assignment' => null,
            'capital_price' => $cart->capital_price
        ];
    }
    // now pass it
    $order->cart()->createMany($items);
    // done, now we can update the inquiry status to deal
    Enquiries::find($obj->id)->update([
        'status' => 'deal'
    ]);
    // whoops, dont forget to send an email
    // first we prepare the pdf first
    $pdfLoc = public_path('/pdf/'.$order->order_number.'.pdf');
    PDF::loadFile(config('app.url').'/view-invoice/'.$order->order_number)
    ->setOption('margin-bottom', 0)
    ->setOption('margin-left', 0)
    ->setOption('margin-top', 0)
    ->setOption('margin-right', 0)
    ->save($pdfLoc);
    // then prepare all the data
    $website = Website::find($obj->website)->first()->toArray();
    $data = [
        'website' => $website,
        'order' => $order->toArray(),
        'cart' => $items,
        'customerData' => $customerData->toArray(),
        'pdf' => $pdfLoc
    ];
    // now send to admins ( vendor mail inboxes )
    $sales = json_decode($website['sales_inboxes']);
    $master = json_decode($website['mail_inboxes']);
    Mail::to($sales)->cc($master)->queue(new OrderCreatedNotifyAdmin($data));
    // now send to customer
    Mail::to($customerData['email'])->queue(new OrderCreatedNotifyCustomer($data));
    // end send email
    if($order){
        // broadcast to all user
        BroadcastNotification([
            'order-management'
        ]," Has created a new Order : #".$order->order_number." from inquiry #".$obj->_id);
        $response = [
            'success' => true,
            'msg' => 'Order Saved'
        ];
    }else{
        $response = [
            'success' => false,
            'msg' => ['Failed to create Order']
        ];
    }
    // return response
    return json_encode($response);
       
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function get($id)
  {
    $cT = Order::with('cart','customerData','payments','payments.user','websiteData','cart.geoProvince','cart.geoCity','cart.geoCountry')->where('order_number',$id)->first();
    if($cT){
      return json_encode([
        'success' => true,
        'order' => $cT
      ]);
    }else{
      return json_encode([
        'success' => false
      ]);
    }
  }

  public function approve(Request $r){
      $update = Order::where('order_number',$r->id)->update([
          'status' => 'approved'
      ]);
      // broadcast
      BroadcastNotification([
        'order-management'
      ]," Approve an Order : #".$r->id);
      // broadcast to purchasing
      BroadcastNotification([
        'purchase-request-management'
      ]," There is new Purchase Request from Order #".$r->id);
      return json_encode([
          'success' => true,
          'msg' => 'The Order has been approved'
      ]);
  }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $r)
  {
    $obj = json_decode($r->data);
    /** 
     * Update The Main Order First
     */
    // unique code for payment 
    $un = randNum(3);
    // modify total
    $total = substr($obj->total, 0, -3) . $un;
    $order = Order::updateOrCreate(['id'=>$obj->id],[
        'total' =>$total,
        'website' => $obj->website,
        'real_invoice' => $obj->real_invoice,
        'order_number' => $obj->order_number,
        'customer_id' => $obj->customer_id,
        'owner' => $obj->owner,
        'status' => $obj->status,
        'payment_status' => $obj->payment_status,
        'inquiry_id' => $obj->inquiry_id,
        'unique_code' => $un
    ])->fresh();
    /** 
     * Then Update It Customer
     */
    $cD = $obj->customerData;
    $order->customerData()->update([
        'name' => $cD->name,
        'email' => $cD->email,
        'phone' => $cD->phone,
        'type' => $cD->type,
        'company_type' => (isset($cD->company_type)?$cD->company_type:null),
        'company_name' => (isset($cD->company_name)?$cD->company_name:null),
        'company_email' => (isset($cD->company_email)?$cD->company_email:null),
        'company_phone' => (isset($cD->company_phone)?$cD->company_phone:null),
        'company_address' => (isset($cD->company_address)?$cD->company_address:null)
    ]);
    // next we save the items
    $items = [];
    foreach($obj->cart as $cart){
        $current_cart = [
            'product_id' => $cart->product_id,
            'name' => $cart->name,
            'price' => $cart->price,
            'qty' => $cart->qty,
            'image' => $cart->image,
            'product_code' => $cart->product_code,
            'subtotal' => $cart->subtotal,
            'shipping_cost' => $cart->shipping_cost,
            'shipping_expedition' => $cart->shipping_expedition,
            'shipping_address' => $cart->shipping_address,
            'sender_name' => $cart->sender_name,
            'greetings' => $cart->greetings,
            'receiver_name' => $cart->receiver_name,
            'receiver_phone' => $cart->receiver_phone,
            'notes' => (isset($cart->notes)?$cart->notes:''),
            'city' => $cart->city,
            'country' => $cart->country,
            'province' => $cart->province,
            'date_time' => $cart->date_time,
            'assignment' => null,
            'capital_price' => $cart->capital_price
        ];
        
        $items[] = $current_cart;
    }
    
    // now pass it
    $order->syncOrderItems($items);
    // whoops, dont forget to send an email
    // first we prepare the pdf first
    $pdfLoc = public_path('/pdf/'.$order->order_number.'.pdf');
    PDF::loadFile(config('app.url').'/view-invoice/'.$order->order_number)
    ->setOption('margin-bottom', 0)
    ->setOption('margin-left', 0)
    ->setOption('margin-top', 0)
    ->setOption('margin-right', 0)
    ->save($pdfLoc,true);
    // then prepare all the data
    $website = Website::find($obj->website)->first()->toArray();
    $data = [
        'website' => $website,
        'order' => $order->toArray(),
        'cart' => $items,
        'customerData' => (array) $cD,
        'pdf' => $pdfLoc
    ];
    // now send to admins ( vendor mail inboxes )
    $sales = json_decode($website['sales_inboxes']);
    $master = json_decode($website['mail_inboxes']);
    
    Mail::to($sales)->cc($master)->queue(new OrderUpdatedNotifyAdmin($data));
    // now send to customer
    Mail::to($cD->email)->queue(new OrderUpdatedNotifyCustomer($data));
    // end send email
    if($order){
        // broadcast to all user
        BroadcastNotification([
            'order-management'
        ]," Has update an Order : #".$order->order_number);
        $response = [
            'success' => true,
            'msg' => 'Order Saved'
        ];
    }else{
        $response = [
            'success' => false,
            'msg' => ['Failed to create Order']
        ];
    }
    // return response
    return json_encode($response);
      
    
  }


}

?>
