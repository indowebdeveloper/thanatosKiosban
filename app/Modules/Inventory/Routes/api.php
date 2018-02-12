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

// Product Category Management Routing
Route::group(['prefix' => '/categories','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','ProductCategoryController@listAjax');
    Route::get('/all','ProductCategoryController@all');
    Route::get('/get/{id}','ProductCategoryController@get');
    Route::post('/create','ProductCategoryController@create');
    Route::post('/update','ProductCategoryController@update');
    Route::post('/destroy','ProductCategoryController@destroy');
});
Route::group(['prefix' => '/products','middleware'=>'auth'], function () {
    // datatable
    Route::get('/datatable','ProductsController@listAjax');
    Route::get('/get/{id}','ProductsController@get');
    Route::post('/create','ProductsController@create');
    Route::post('/update','ProductsController@update');
    Route::post('/destroy','ProductsController@destroy');
    Route::get('/search','ProductsController@search');
});
//Route::middleware('auth')->get('/getRole/{id}', 'RoleManagement@getRole');
//Route::middleware('auth')->post('/saveRole', 'RoleManagement@saveRole');
//Route::middleware('auth')->post('/removeRole', 'RoleManagement@removeRole');
