<?php

namespace Thanatos\Http\Controllers;

use Illuminate\Http\Request;
use Thanatos\User as User;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use DataTables as Datatables;
use Validator;
use Auth;

class UserManagement extends Controller
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

    /**
     * Used For Get User Details
     */


    public function getUser($id){
        $user = User::where('id',$id)->first();
        $roleId = "";
        foreach($user->roles as $role){
           $roleId = $role->id;
        }
        $user->role = $roleId;
        return json_encode(array(
          'userData' => $user
        ));
    }

    public function removeUser(Request $request){
      $uid = $request->uid;
      if($uid>1){
        $user = User::find($uid);
        $user->delete();
        return json_encode(array(
            "status" => 'success'
        ));
      }else{
        return json_encode(array(
            "status" => $request->uid
        ));
      }

    }

    public function saveUser(Request $request){
        // Check, if UID is not empty, then its an update request, otherwise is new user
        $failed = false;
        if(!empty($request->id)){
            // Update Record first
            $mainvalidate = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255'
            ]);
            if(!$mainvalidate->fails()){
              User::where('id', '=', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email
              ]);
            }else{
              $failed = true;
            }
            if(!$failed && !empty($request->pass)){
              User::where('id', '=', $request->id)->update([
                'password' => bcrypt($request->pass)
              ]);
            }
            // Apply the Role
            if(!$failed){
                $user = User::where('id','=',$request->id)->first();
                $user->assignRole($request->roleID);
            }


        }else{
            $mainvalidate =  Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'pass' => 'required|min:6',
            ]);
            if(!$mainvalidate->fails()){
              $user = User::create([
                  'name' => $request->name,
                  'email' => $request->email,
                  'password' => bcrypt($request->pass),
              ]);
            }else{
              $failed = true;
            }
            // Apply the Role
            if(!$failed){
                $user = User::where('id','=',$user->id)->first();
                $user->assignRole($request->roleID);
            }
        }
        return json_encode(array(
            "success" => ($failed?false:true),
            'pass' => $request->pass
        ));
    }
    /** List all User **/
    public function listAjax(Request $request){

      if($request->input('role_id')!=""){
        // If there is role filter
        $users = User::whereHas("roles", function($query) use ($request){
           $query->where('roles.id','=',$request->input('role_id'));
        })->select(['id','name','email','created_at','updated_at']);
      }else{
        $users = User::select(['id','name','email','created_at','updated_at']);
      }
      return Datatables::of($users)
      ->addColumn('role', function($users){
            if($users->id==1){
              return 'Root User';
            }else{
              $uRole = array();
              foreach($users->roles as $role){
                    $uRole[] = '<a href="#" data-role-id="'.$role->id.'">'.$role->name.'</a>';
              }
              return implode(", ",$uRole);
            }

      })
      ->addColumn('action', function ($users) {
            $a = '<a href="/#!/users/edit/'.$users->id.'"  class="btn btn-sm btn-primary"><i class="icon-edit"></i></a>';
          if($users->id>1){
            $a .= '<a href="javascript:void()" data-uid="'.$users->id.'" data-uname="'.$users->name.'" class="btn btn-sm btn-danger rm-user" style="margin-left:10px;"><i class="icon-trash-b"></i></a>';
          }
          return $a;
      })
      ->editColumn('created_at',function($users){
           return date('F d Y', strtotime($users->created_at));
      })
      ->editColumn('updated_at',function($users){
           return date('F d Y', strtotime($users->updated_at));
      })
      ->escapeColumns(['action'])
      ->make(false);
    }

}
