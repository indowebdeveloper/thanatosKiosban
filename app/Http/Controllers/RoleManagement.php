<?php

namespace Thanatos\Http\Controllers;

use Illuminate\Http\Request;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use DataTables as Datatables;
use Validator;
use Illuminate\Http\Response;

class RoleManagement extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('hasPermission:user-and-role-administration');
    }



    public function getRole($id){
        $role = Roles::where('id',$id)->first();
        $permissions = array();
        foreach($role->permissions as $permission){
           $permissions[] = $permission->id;
        }
        $role->permission = $permissions;
        return json_encode(array(
          'roleData' => $role
        ));
    }

    public function removeRole(Request $request){
      $id = $request->id;
      $role = Roles::find($id);
      $count = count($role->users);
      // If role is not Root and has no user then proceed
      if($id>1 && $count<1){
        $role->delete();
        return json_encode(array(
            "success" => true
        ));
      }else{
        return json_encode(array(
            "success" => false
        ));
      }

    }

    public function saveRole(Request $request){
        // Check, if UID is not empty, then its an update request, otherwise is new user
        $failed = false;
        if(!empty($request->id)){
            // Update Record first
            $mainvalidate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required'
            ]);
            if(!$mainvalidate->fails()){
              Roles::where('id', '=', $request->id)->update([
                'name' => $request->name,
                'description' => $request->description
              ]);
            }else{
              $failed = true;
            }
            // Apply the Permission
            if(!$failed && $request->id!='1'){
              if(empty($request->permission)){
                  $request->permission = [];
              }

              $role = Roles::where('id','=',$request->id)->first();
              $role->syncPermissions($request->permission);
            }


        }else{
            $mainvalidate =  Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required'
            ]);
            if(!$mainvalidate->fails()){
              $role = Roles::create([
                  'name' => $request->name,
                  'description' => $request->description,
                  'slug' => str_slug($request->name),
              ]);
            }else{
              $failed = true;
            }
            // Apply the Permission
            if(is_array($request->permission) && !$failed){
                $role = Roles::where('id','=',$role->id)->first();
                $role->syncPermissions($request->permission);
            }
        }
        return json_encode(array(
            "success" => ($failed?false:true)
        ));
    }
    /**
     * Return all roles in JSON
     */
    public function getAllRolesJSON(){
      $roles = Roles::select(['id','name'])->get();
      return response()->json(["roles"=>$roles]);
    }
    /**
     * Return all permissions in JSON
     */
    public function getAllPermissionsJSON(){
      $permissions = Permissions::select(['id','name'])->get();
      return response()->json(["permissions"=>$permissions]);
    }

    /**
     * Return All Roles in DataTables
     */
    public function listAjax(){
        $roles = Roles::select(['id','name','description']);
        return Datatables::of($roles)
        ->addColumn('action', function ($roles) {

                $a = '<a href="/#!/roles/edit/'.$roles->id.'" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                if($roles->id>1){
                $a .= '<a href="javascript:void()" data-id="'.$roles->id.'" data-name="'.$roles->name.'" class="btn btn-sm btn-danger rm-role" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
                }
                return $a;
            })
      ->escapeColumns(['action'])
      ->make(false);
    }

}
