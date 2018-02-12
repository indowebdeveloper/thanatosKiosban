<?php

namespace Thanatos\Modules\PointOfSales\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\PointOfSales\Models\Enquiries as Enquiries;
use Thanatos\Modules\PointOfSales\Models\Order as Order;
use Thanatos\Modules\PointOfSales\Models\OrderItem as OrderItem;
use Thanatos\Modules\PointOfSales\Models\OrderCustomer as OrderCustomer;
use Thanatos\Modules\PointOfSales\Models\Website as Vendor;
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


class InvoiceViewer extends Controller {

  
  public function get($id)
  {
    $cT = Order::with('cart','customerData','payments','websiteData','cart.geoCity','cart.geoCountry','cart.geoProvince')->where('order_number',$id)->first()->toArray();
    if($cT){
      
        return view('point-of-sales::invoice',['data'=>$cT]);
    }else{
      return json_encode([
        'success' => false,
        'msg' => 'Invoice Not Found'
      ]);
    }
  }

  


}

?>
