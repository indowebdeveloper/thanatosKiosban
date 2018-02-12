<?php

namespace Thanatos\Modules\PointOfSales\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Thanatos\Modules\PointOfSales\Models\Enquiries as Enquiries;
use Thanatos\Modules\Customer\Models\Customers as Customers;
use DataTables as Datatables;
use Validator;
use Auth;
use Illuminate\Http\Response;
use Thanatos\Http\Controllers\Controller as Controller;
use MongoDB\BSON\UTCDateTime as MongoDate;
use Igaster\LaravelCities\Geo;
use DateTime;

class EnquiriesController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:inquiry-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $r)
  {
    $eQ = Enquiries::when(($r->date_from!="" && $r->date_to!=""), function ($query) use ($r) {
        return $query->whereBetween('created_at', [new MongoDate(new DateTime($r->date_from." 00:00:00")),new MongoDate(new DateTime($r->date_to.' 23:59:59'))]);
    })
    ->when(($r->status!=""), function ($query) use ($r) {
        return $query->where('status', $r->status);
    })
    ->when(($r->user!=""), function ($query) use ($r) {
        return $query->where('owner', (int)$r->user);
    })->get();  
    return Datatables::of($eQ)
    ->addColumn('action', function ($cT) {
          $a = "";
          if($cT->status=='pending'){
            $a .= '<a href="/'.config('app.admin_url').'#!/enquiries/edit/'.$cT->_id.'"  class="btn btn-sm btn-primary" title="Edit Inquiry"><i class="icon-edit"></i></a>';
            $a .= '<a href="/'.config('app.admin_url').'#!/orders/create/'.$cT->_id.'"  style="margin-left:10px;" class="btn btn-sm btn-success" title="Create Order"><i class="icon-android-open"></i></a>';
          }
          $a .= '<a href="/'.config('app.admin_url').'#!/enquiries/view/'.$cT->_id.'"  style="margin-left:10px;" class="btn btn-sm btn-default" title="View Inquiry"><i class="icon-eye"></i></a>';
          return $a;
    })
    ->editColumn('owner',function($d){
            return \Thanatos\User::where('id',$d->owner)->first()->name;
    })
    ->editColumn('status',function($d){
        switch($d->status){
            case "pending":
            return "<span class='tag tag-warning'>PENDING</span>";
            break;
            case "expired":
            return "<span class='tag tag-default'>EXPIRED</span>";
            break;
            case "deal":
            return "<span class='tag tag-success'>DEAL</span>";
            break;
        }
    })
    ->editColumn('total',function($d){
        return toCurrency($d->total);
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
   * create a new Inquiry.
   *
   * @return Response
   */
  public function create(Request $r)
  {
    // since its a mongodb, we need to create the id by ourself, otherwise, it would use the object id
    $obj = json_decode($r->data);
    $obj->status = 'pending';
    $obj->owner = Auth::user()->id;
    $obj->id = nextInquiryId();
    $obj = (array) $obj; // save it as assosiative array 
    $inq = Enquiries::create($obj);    
    if($inq){
        // broadcast notification to anyone who can manage an inquiry
        BroadcastNotification([
            'inquiry-management'
        ]," Has created a new Inquiry");
        $response = [
            'success' => true,
            'msg' => 'Inquiry Saved'
        ];
    }else{
        $response = [
            'success' => false,
            'msg' => ['Failed to create Inquiry']
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
    if(isset($_GET['trans'])){
      $cT = Enquiries::where('_id',$id)->first()->toArray();
      foreach($cT['cart'] as $k=>$v){
        $cT['cart'][$k]['country'] = Geo::find($cT['cart'][$k]['country'])->name;
        $cT['cart'][$k]['city'] = Geo::find($cT['cart'][$k]['city'])->name;
        $cT['cart'][$k]['province'] = Geo::find($cT['cart'][$k]['province'])->name;
      }
    }else{
      $cT = Enquiries::where('_id',$id)->first();
    }
    if($cT){
      return json_encode([
        'success' => true,
        'inquiry' => $cT
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
    $obj = (array) $obj; // save it as assosiative array
    $target_id = $obj['id'];
    // remove key that should not be updated manually
    unset($obj['_id']);
    unset($obj['created_at']); 
    unset($obj['updated_at']); 
    // now we are ready to update 
    $inq = Enquiries::find($target_id)->update($obj);
    if($inq){
         // broadcast notification to anyone who can manage a customer
       BroadcastNotification([
        'inquiry-management'
        ]," Updating Inquiry ( ID : ".$r->_id." ) ");
      // return response
      return json_encode([
        "success" => true,
        "msg" => 'Your data has been updated'
      ]);
    }else{
        return json_encode([
            "success" => false,
            "msg" => 'Your data cant been updated'
        ]);  
    }    
      
    
  }


}

?>
