<?php

namespace Thanatos\Modules\PointOfSales\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'point-of-sales');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'point-of-sales');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'point-of-sales');
        $this->registerMenu();
        $this->registerPermission();
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
            'name' => 'POS Management',
            'description' => 'Access Module POS'
          ),
          array(
            'name' => 'Inquiry Management',
            'description' => 'Permission for access inquiry management'
          ),
          array(
            'name' => 'Add Inquiry',
            'description'=>'Permission for add inquiry'
          ),
          array(
            'name' => 'Edit Inquiry',
            'description'=>'Permission for edit inquiry'
          ),
          array(
            'name' => 'Order Management',
            'description' => 'Permission for access order management'
          ),
          array(
            'name' => 'Add Order',
            'description'=>'Permission for add order'
          ),
          array(
            'name' => 'Edit Order',
            'description'=>'Permission for edit order'
          ),
          array(
            'name' => 'Website Management',
            'description' => 'Access Website Module'
          ),
          array(
            'name' => 'Manage Payment',
            'description' => 'Manage Payment'
          )
        );
        //if(!permissionExists('Manage Users')){
           registerPermission($permission);
      }
      public function registerMenu(){
        $menu = Menu::get('main');
        // Register menu
        $menu->add('Point Of Sales')
        ->prepend('<i class="icon-cash"></i> ')
        ->data([
          'permission' => 'pos-management',
          'order' => 3
        ]);
        // Register Sub Menu
        $menu->pointOfSales->add('Enquiries')->data(['permission'=>'inquiry-management'])->link->href('/enquiries');
        $menu->pointOfSales->add('Orders')->data(['permission'=>'order-management'])->link->href('/orders');
        $menu->pointOfSales->add('Websites')->data(['permission'=>'website-management'])->link->href('/websites');
        // Reorder menu
        $menu->sortBy('order');
      }
}
