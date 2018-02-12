<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/suppliers','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','SupplierController@listAjax');
    Route::get('/get/{id}','SupplierController@get');
    Route::post('/create','SupplierController@create');
    Route::post('/update','SupplierController@update');
    Route::post('/destroy','SupplierController@destroy');
    Route::get('/search',function(Request $r){
        $supplier =  Thanatos\Modules\Purchasing\Models\Suppliers::where('name','like','%'.$r->s.'%')
        ->with('geoCity','geoCountry','geoProvince')
        ->when(($r->country!="" && $r->country!="null"), function ($query) use ($r) {
            return $query->where('country', $r->country);
        })
        ->when(($r->province!="" && $r->province!="null"), function ($query) use ($r) {
            return $query->where('province', $r->province);
        })
        ->when(($r->city!="" && $r->city!="null"), function ($query) use ($r) {
            return $query->where('city', $r->city);
        })
        ->orderBy('this_week_orders','ASC')
        ->paginate(10);
        return json_encode($supplier);
    });
});
Route::group(['prefix' => '/purchase-requests','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','PurchaseRequestController@listAjax');
    Route::get('/get/{id}','PurchaseRequestController@get');
});

Route::group(['prefix' => '/purchase-orders','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','PurchaseOrderController@listAjax');
    Route::get('/get/{id}','PurchaseOrderController@get');
    Route::post('/create/{id}','PurchaseOrderController@create');
    Route::post('/update','PurchaseOrderController@update');
    Route::post('/destroy','PurchaseOrderController@destroy');
});