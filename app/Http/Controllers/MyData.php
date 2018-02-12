<?php

namespace Thanatos\Http\Controllers;

use Illuminate\Http\Request;
use Thanatos\User as User;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Auth;

class MyData extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Used to get current user details
     */
     public function myPermission(){
        // Get current USER
        $user = Auth::user();
        // Get Current user role id
        $roles = Auth::user()->roles;
        foreach($roles as $role){
           $roleID = $role->id;
        }
        // Get the role permission
        $role = Roles::find($roleID);
        // if its "GodMode" return all permission then
        if($role->can('god-mode')){
          $permissions = Permissions::select(['slug'])->get();
          foreach($permissions as $myPermission){
            $permission[] = $myPermission->slug;
          }
        }else{
          $permission = $role->getPermissions();
        }
        // return the data
        return json_encode(array(
          'permissions'  => $permission
        ));

     }

}
