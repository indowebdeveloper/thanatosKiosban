<?php
use Illuminate\Support\Facades\Schema;
function hasPermission($param){
	if(Auth::user()->can($param) || Auth::user()->can('god-mode')){
		return true;
	}else{
		return false;
	}
}
function registerPermission($permissionReq){
    if(Schema::hasTable('permissions')){
      if(is_array($permissionReq)){
        foreach($permissionReq as $permission){
            Caffeinated\Shinobi\Models\Permission::firstOrCreate([
              "name" => $permission['name'],
              "slug" => str_slug($permission['name']),
              "description" => $permission['description']
            ]);
        }
      }else{
        Caffeinated\Shinobi\Models\Permission::firstOrCreate([
          "name" => $permissionReq,
          "slug" => str_slug($permissionReq)
        ]);
      }
    }
}
function permissionExists($name){
  if(Caffeinated\Shinobi\Models\Permission::where('name', '=', $name)->count() > 0){
    return true;
  }else{
    return false;
  }
}

function getSettings($meta_key,$attribute){
    $settings = Thanatos\Settings::where("meta_key","=",$meta_key)->first();
    if($settings){
      $settings = json_decode($settings->meta_value,true);
      return $settings[$attribute];
    }
    
}
function geoData($id){
  return Igaster\LaravelCities\Geo::where('id',$id)->first();
}

function toCurrency($n){
  $cur = getSettings('general-settings','currency');
  return $cur.'. '.number_format((float) $n, 0, ".", ".");
}

/**
 * Used for broadcast notification
 *
 * @param array $permission permission required for viewing the notification
 * @param string $msg Message of the notification 
 * @param string $action_url Url for click action, not mandatory
 * @return void
 */
function BroadcastNotification($permission,$msg,$action_url=""){
  $data = [
    "sender_id" => Auth::user()->id,
    "permissions" => $permission,
    "message" => Auth::user()->name." ( ".Auth::user()->email." ) ".$msg,
    "url" => $action_url
  ];
  $redis = Redis::connection();
  $redis->publish('notification',json_encode($data));
  return "ok";
}

function nextInquiryId(){
  $collection = Thanatos\Modules\PointOfSales\Models\Enquiries::orderBy('created_at','desc')->first();
  if(count($collection)>0){
     return ($collection->id+1);
  }else{
     return 1;
  }
}

function randNum($digits){
  return rand(pow(10, $digits-1), pow(10, $digits)-1);
}