<?php

namespace Thanatos\Modules\Purchasing\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

use Menu;
use Caffeinated\Shinobi\Models\Permission as Permission;
use Auth;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'purchasing');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'purchasing');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'purchasing');
        $this->registerPermission();
        $this->registerMenu();
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function registerPermission(){

        // Register Permission
        $permission = array(
          array(
            'name' => 'Purchasing Management',
            'description' => 'Permission for access purchasing module'
          ),
          array(
            'name' => 'Purchase Request Management',
            'description'=>'Permission for access module PR'
          ),
          array(
            'name' => 'Purchase Order Management',
            'description'=>'Permission for access module PO'
          ),
          array(
            'name' => 'Purchase Order Approval',
            'description'=>'Permission for approving purchase order'
          ),
          array(
            'name' => 'Add Purchase Order',
            'description'=>'Permission for create purchase order'
          ),
          array(
            'name' => 'Edit Purchase Order',
            'description'=>'Permission for edit purchase order'
          ),
          array(
            'name' => 'Delete Purchase Order',
            'description'=>'Permission for delete purchase order'
          ),
          array(
            'name' => 'Supplier Management',
            'description'=>'Permission for access module supplier'
          ),
          array(
            'name' => 'Add Supplier',
            'description'=>'Permission for add suplier'
          ),
          array(
            'name' => 'Edit Supplier',
            'description'=>'Permission for edit supplier'
          ),
          array(
            'name' => 'Delete Supplier',
            'description'=>'Permission for delete supplier'
          )
        );
        //if(!permissionExists('Manage Users')){
           registerPermission($permission);
      }
      public function registerMenu(){
        $menu = Menu::get('main');
        // Register menu
        $menu->add('Purchasing')
        ->prepend('<i class="icon-cart"></i> ')
        ->data([
          'permission' => 'purchasing-management',
          'order' => 2
        ]);
        // Register Sub Menu
        $menu->purchasing->add('Purchase Requests')->data(['permission'=>'purchase-request-management'])->link->href('/purchase-requests');
        $menu->purchasing->add('Purchase Order')->data(['permission'=>'purchase-order-management'])->link->href('/purchase-order');
        $menu->purchasing->add('Suppliers')->data(['permission'=>'supplier-management'])->link->href('/suppliers');
        // Reorder menu
        $menu->sortBy('order');
      }
}
