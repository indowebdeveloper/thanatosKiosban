<?php

namespace Thanatos\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Menu;
use Caffeinated\Shinobi\Models\Permission as Permission;
use Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */


     public function boot()
     {
         // FIX BUG LARAVEL
         Schema::defaultStringLength(191);
         // WE USE BOOT FOR BUILD OUR MENUS
         $this->buildMenu();
         // Register Permission
         $permission = array(
           array(
             'name' => 'User And Role Administration',
             'description' => 'Permission for manage users and permissions'
           ),
           array(
             'name' => 'Manage General Settings',
             'description'=>'Manage General Settings'
           )
         );
         //if(!permissionExists('Manage Users')){
            registerPermission($permission);
         //}
     }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      if ($this->app->environment() == 'local') {
        $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
      }
    }
    /**
     * Method for creating menu
     */
     public function buildMenu(){
       /**
        * Build Main Menu for the APPS
        */
         Menu::make('main', function($menu) {
           $menu->add('Dashboard')
           ->prepend('<i class="icon-home"></i> ')
           ->data('order',1)
           ->link->href('/');
           // Menu and submenus for Users
           $menu->add('Users')
           ->prepend('<i class="icon-head"></i> ')
           ->data([
             'permission' => 'user-and-role-administration',
             'order'  => 2
           ]);
           // Here we add the sub menus
           $menu->users->add('List User')->data(['permission'=>'user-and-role-administration'])->link->href('/users');
           $menu->users->add('List Roles')->data(['permission'=>'user-and-role-administration'])->link->href('/roles');
           // Menu and submenus for Settings
           $menu->add('Settings')
           ->prepend('<i class="icon-cog"></i> ')
           ->data([
             'permission' => 'manage-general-settings',
             'order'  => 999999
           ]);
           // Here we add the sub menus
           $menu->settings->add('General Settings')->data(['permission'=>'manage-general-settings'])->link->href('/settings');
         })->sortBy('order');

     }
}
