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

Route::group(['prefix' => '/customers','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','CustomersController@listAjax');
    Route::get('/all','CustomersController@all');
    Route::get('/get/{id}','CustomersController@get');
    Route::post('/create','CustomersController@create');
    Route::post('/update','CustomersController@update');
    Route::post('/destroy','CustomersController@destroy');
    Route::get('/searchByEmailOrPhone',function(Request $r){
        $c = Thanatos\Modules\Customer\Models\Customers::where("email",$r->e)
        ->orWhere('phone',$r->p)
        ->first();
        return json_encode([
            'customer' => $c
        ]);
    });
});
Route::group(['prefix' => '/company-types','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','CompanyTypesController@listAjax');
    Route::get('/get/{id}','CompanyTypesController@get');
    Route::post('/create','CompanyTypesController@create');
    Route::post('/update','CompanyTypesController@update');
    Route::post('/destroy','CompanyTypesController@destroy');
    Route::get('/all','CompanyTypesController@all');
});