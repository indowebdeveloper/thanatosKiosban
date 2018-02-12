<?php

namespace Thanatos\Http\Controllers;

use Thanatos\Settings as Settings;
use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Validator;
use Illuminate\Http\Response;
use Auth;
use Image;

class SettingsManagement extends Controller {
  /**
   * As usualy, set the permission
   */
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function getSetting($metaKey){
      $settings = Settings::where('meta_key',$metaKey)->first();
      if($settings){
        $value = json_decode($settings->meta_value);  
      }else{
        $value = [];
      }
      return json_encode(array(
        'settings' => $value
      ));
  }

  public function saveSetting(Request $request, $metaKey){
    $msg = "";
    $success = false;
    // If general settings
    if($metaKey == 'general-settings' && (Auth::user()->can('god-mode') || Auth::user()->can('manage-general-settings')) ){
      // Get Logo variable
      $logo_light = $request->get('logo_light');
      $logo_dark = $request->get('logo_dark');
      $logo_dark_small = $request->get('logo_dark_small');
      // if isPath is not present, then upload the new image as logo
      if(!isset($logo_light['isPath'])){
        Image::make($request->logo_light["src"])->save(public_path('/assets/images/system/logo_light.png'));
      }
      if(!isset($logo_dark['isPath'])){
        Image::make($request->logo_dark["src"])->save(public_path('/assets/images/system/logo_dark.png'));
      }
      if(!isset($logo_dark_small['isPath'])){
        Image::make($request->logo_dark_small["src"])->save(public_path('/assets/images/system/logo_dark_small.png'));
      }
      // After that capture the all request
      $data = $request->all();
      // Modify the logo data
      $data["logo_light"] = "/assets/images/system/logo_light.png";
      $data["logo_dark"] = "/assets/images/system/logo_dark.png";
      $data["logo_dark_small"] = "/assets/images/system/logo_dark_small.png";
      // make data as json
      $data = json_encode($data);
      // Update the data
      $update = Settings::where('meta_key','=','general-settings')->update(['meta_value'=>$data]);
      $success = ($update?true:false);
      if(!$success){
        $msg = "Failed to update general settings, check your data";
      }
    }

    // If not Permitted
    if(!$success && $msg == ""){
      $msg = "You are not permitted, permission denied";
    }

    // Return the data
    return json_encode(array(
      'success' => $success,
      'msg' => $msg,
      'key' => $metaKey
    ));
  }

}

?>
