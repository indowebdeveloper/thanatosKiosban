<?php

namespace Thanatos\Modules\Customer\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'customer');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'customer');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'customer');
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
            'name' => 'Customer Management',
            'description' => 'Permission for access customer management'
          ),
          array(
            'name' => 'Add Customer',
            'description'=>'Permission for add customer'
          ),
          array(
            'name' => 'Edit Customer',
            'description'=>'Permission for edit customer'
          ),
          array(
            'name' => 'Company Type Management',
            'description' => 'Permission for access company type management'
          ),
          array(
            'name' => 'Add Company Type',
            'description'=>'Permission for add company type'
          ),
          array(
            'name' => 'Edit Company Type',
            'description'=>'Permission for edit company type'
          )
        );
        //if(!permissionExists('Manage Users')){
           registerPermission($permission);
      }
      public function registerMenu(){
        $menu = Menu::get('main');
        // Register menu
        $menu->add('Customers')
        ->prepend('<i class="icon-android-contacts"></i> ')
        ->data([
          'permission' => 'customer-management',
          'order' => 3
        ]);
        // Register Sub Menu
        $menu->customers->add('All Customer')->data(['permission'=>'customer-management'])->link->href('/customers');
        $menu->customers->add('Add Customer')->data(['permission'=>'add-customer'])->link->href('/customers/create');
        $menu->customers->add('All Company Type')->data(['permission'=>'company-type-management'])->link->href('/company_types');
        $menu->customers->add('Add Company Type')->data(['permission'=>'add-company-type'])->link->href('/company_types/create');
        // Reorder menu
        $menu->sortBy('order');
      }
}
