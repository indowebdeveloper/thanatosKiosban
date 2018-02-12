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
use Thanatos\Mail\OrderPaymentPaidNotifyAdmin;
use Thanatos\Mail\OrderPaymentPaidNotifyCustomer;
use Thanatos\Mail\OrderPaymentNotifyAdmin;
use Thanatos\Mail\OrderPaymentNotifyCustomer;
use PDF;

class OrderPaymentsController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:manage-payment');
  }


  /**
   * create a new Order.
   *
   * @return Response
   */
  public function create($id,Request $r)
  {
    // first get the order
    $order = Order::where('order_number',$id)->with('customerData')->first();
    // Insert The Payment
    $payment = new OrderPayments([
        'transaction_date' => $r->transaction_date,
        'transaction_id' => $r->transaction_id,
        'payment_method' => $r->payment_method,
        'notes' => $r->notes,
        'amount' => $r->amount,
        'user_id' => Auth::id()
    ]);
    // assign into the order
    $order->payments()->save($payment);
    // load vendor information
    $website = Website::find($order->website)->first()->toArray();
    $master = json_decode($website['mail_inboxes']);
    $finances = json_decode($website['finance_inboxes']);
    $sales = json_decode($website['sales_inboxes']);
    $slaves = array_merge($sales,$finances);
    // check if the payment is equal or more than the total due, if so, update the order status as well
    $total_payment = $order->payments()->sum('amount');
    if($total_payment >= $order->total){
        // update status to paid
        Order::where('order_number',$id)->update([
            'status' => 'paid'
        ]);
        // regenerate pdf with status paid and send email    
        $pdfLoc = public_path('/pdf/'.$order->order_number.'.pdf');
        PDF::loadFile(config('app.url').'/view-invoice/'.$order->order_number)
        ->setOption('margin-bottom', 0)
        ->setOption('margin-left', 0)
        ->setOption('margin-top', 0)
        ->setOption('margin-right', 0)
        ->save($pdfLoc,true);
        // prepare
        $data = [
            'order' => $order->toArray(),
            'customerData' => $order->customerData->toArray(),
            'pdf' => $pdfLoc,
            'website' => $website
        ];
        Mail::to($slaves)->cc($master)->queue(new OrderPaymentPaidNotifyAdmin($data));
        Mail::to($order->customerData->email)->cc($master)->queue(new OrderPaymentPaidNotifyCustomer($data));
        // end
    }
    // now send payment confirmation
    $data = [
        'order' => $order->toArray(),
        'customerData' => $order->customerData->toArray(),
        'website' => $website,
        'trans' => [
            'transaction_id' => $r->transaction_id,
            'transaction_date' => $r->transaction_date,
            'method' => $r->payment_method,
            'amount' => $r->amount,
            'notes' => $r->notes
        ] 
    ];
    Mail::to($finances)->cc($master)->queue(new OrderPaymentNotifyAdmin($data));
    Mail::to($order->customerData->email)->cc($master)->queue(new OrderPaymentNotifyCustomer($data));
    // end send email
    if($order){
        // broadcast to all user
        BroadcastNotification([
            'manage-payment'
        ]," Has created a new Payment for order : #".$order->order_number);
        $response = [
            'success' => true,
            'msg' => 'Payment Added'
        ];
    }else{
        $response = [
            'success' => false,
            'msg' => ['Failed to Add Payment']
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
    $cT = Order::with('cart','customerData','payments','payments.user','website','cart.geoProvince','cart.geoCity','cart.geoCountry')->where('order_number',$id)->first();
    
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
    $order = Order::updateOrCreate(['id'=>$obj->id],[
        'total' =>$obj->total,
        'website' => $obj->website,
        'real_invoice' => $obj->real_invoice,
        'order_number' => $obj->order_number,
        'customer_id' => $obj->customer_id,
        'owner' => $obj->owner,
        'status' => $obj->status,
        'inquiry_id' => $obj->inquiry_id
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
            'date_time' => $cart->date_time
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
    $admins = json_decode($website['mail_inboxes']);
    Mail::to($admins)->send(new OrderUpdatedNotifyAdmin($data));
    // now send to customer
    Mail::to($cD->email)->send(new OrderUpdatedNotifyCustomer($data));
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
