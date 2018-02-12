<?php

namespace Thanatos\Modules\Inventory\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'inventory');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'inventory');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'inventory');
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
          'name' => 'Product Management',
          'description' => 'Permission for access product management'
        ),
        array(
          'name' => 'Add Product',
          'description'=>'Permission for add product'
        ),
        array(
          'name' => 'Edit Product',
          'description'=>'Permission for edit product'
        ),
        array(
          'name' => 'Delete Product',
          'description'=>'Permission for delete product'
        ),
        array(
          'name' => 'Manage Product Category',
          'description'=>'Permission for manage product category'
        ),
        array(
          'name' => 'Warehouse Management',
          'description'=>'Permission for access warehouse management'
        ),
        array(
          'name' => 'Add Warehouse',
          'description'=>'Permission for add warehouse'
        ),
        array(
          'name' => 'Edit Warehouse',
          'description'=>'Permission for edit warehouse'
        ),
        array(
          'name' => 'Delete Warehouse',
          'description'=>'Permission for delete warehouse'
        )
      );
      //if(!permissionExists('Manage Users')){
         registerPermission($permission);
    }
    public function registerMenu(){
      $menu = Menu::get('main');
      // Register menu
      $menu->add('Products')
      ->prepend('<i class="icon-qrcode"></i> ')
      ->data([
        'permission' => 'product-management',
        'order' => 2
      ]);
      // Register Sub Menu
      $menu->products->add('All Product')->data(['permission'=>'product-management'])->link->href('/products');
      $menu->products->add('Add Product')->data(['permission'=>'add-product'])->link->href('/products/create');
      $menu->products->add('Product Categories')->data(['permission'=>'manage-product-category'])->link->href('/product-categories');
      // Reorder menu
      $menu->sortBy('order');
    }
}
