<?php

namespace Thanatos;
use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Caffeinated\Shinobi\Models\Role as Roles;
use Caffeinated\Shinobi\Models\Permission as Permissions;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
     use ShinobiTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
       return $permission;

    }
}
