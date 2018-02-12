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

Route::group(['prefix' => '/enquiries','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','EnquiriesController@listAjax');
    Route::get('/get/{id}','EnquiriesController@get');
    Route::post('/create','EnquiriesController@create');
    Route::post('/update','EnquiriesController@update');
    Route::post('/destroy','EnquiriesController@destroy');
    Route::get('/all','EnquiriesController@all');
    // public api
    Route::get('/get-user/{id}',function($id){
        return json_encode([
          'user' =>  \Thanatos\User::find($id)->first()
        ]);
    });
    Route::get('/get-company-type/{id}',function($id){
        $cT = \Thanatos\Modules\Customer\Models\CompanyType::find($id)->first();
        return json_encode([
            'ct' => $cT
        ]);
    });
});
Route::group(['prefix' => '/orders','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','OrderController@listAjax');
    Route::get('/get/{id}','OrderController@get');
    Route::post('/create','OrderController@create');
    Route::post('/approve','OrderController@approve');
    Route::post('/update','OrderController@update');
    Route::post('/destroy','OrderController@destroy');
    Route::get('/all','OrderController@all');
    // public api
    Route::get('/get-user/{id}',function($id){
        return json_encode([
          'user' =>  \Thanatos\User::find($id)->first()
        ]);
    });
    Route::get('/get-company-type/{id}',function($id){
        $cT = \Thanatos\Modules\Customer\Models\CompanyType::find($id)->first();
        return json_encode([
            'ct' => $cT
        ]);
    });
});
Route::group(['prefix' => '/payments','middleware'=>'auth'], function () {
    // datatable
    Route::post('/add-payment/{id}','OrderPaymentsController@create');
    Route::post('/destroy','OrderPaymentsController@destroy');
});
Route::group(['prefix' => '/websites','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','WebsiteController@listAjax');
    Route::get('/get/{id}','WebsiteController@get');
    Route::post('/create','WebsiteController@create');
    Route::post('/update','WebsiteController@update');
    Route::post('/destroy','WebsiteController@destroy');
    Route::get('/all','WebsiteController@all');
    // public api
    Route::get('/get-website/{id}',function($id){
        return json_encode([
          'websites' =>  \Thanatos\Modules\PointOfSales\Models\Website::find($id)->first()
        ]);
    });
});