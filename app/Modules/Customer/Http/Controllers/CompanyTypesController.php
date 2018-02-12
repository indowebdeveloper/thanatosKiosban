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

class CompanyTypesController extends Controller {

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('hasPermission:company-type-management');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function listAjax(Request $request)
  {
    $cT = CompanyType::select(['id','name','description']);
    return Datatables::of($cT)
    ->addColumn('action', function ($cT) {
          $a = '<a href="/'.config('app.admin_url').'#!/company_types/edit/'.$cT->id.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
          $a .= '<a href="javascript:void()" data-id="'.$cT->id.'" data-name="'.$cT->name.'" class="btn btn-sm btn-danger rm-type" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
          return $a;
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
    $cT = CompanyType::all();
    return json_encode([
        'company_types' => $cT
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
      $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255'
      ]);
      if(!$mainvalidate->fails()){
        $cT = CompanyType::create([
            'name' => $r->name,
            'description' => $r->description
        ]);
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
    $cT = CompanyType::where('id',$id)->first();
    if($cT){
      return json_encode([
        'success' => true,
        'company_types' => $cT
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
    $mainvalidate = Validator::make($r->all(), [
        'name' => 'required|max:255',
        'id' => 'required|exists:company_type'
    ]);
    if(!$mainvalidate->fails()){
      $update = CompanyType::where('id',$r->id)
      ->update([
        'name' => $r->name,
        'description' => $r->description
      ]);
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
    $count =  Customers::where('company_type',$r->id)->count();
    if($count>0){
      return json_encode([
        "success" => false,
        "msg" => "The Type has being used, you cant remove it"
      ]);
    }else{
      $cat = CompanyType::where('id',$r->id)->delete();
      return json_encode([
        "success" => true,
        "msg" => "The company has been successfully removed"
      ]);
    } 
    
  }

}

?>
