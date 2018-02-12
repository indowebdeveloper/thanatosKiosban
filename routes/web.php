<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes(); // Normal Auth Route
if(config("app.admin_url")!=""){
Route::group(['prefix' => config("app.admin_url")], function () {
    Route::get('/','AppController@index');
});
}else{
    Route::get('/', 'AppController@index');    
}
Route::get('/hajar', function () {
    $data = [
      "sender" => '12',
      "permissions" => ["manage-general-settings"],
      "message" => "Piee bos",
      "url" => "/users"
    ];
    $redis = Redis::connection();
    $redis->publish('notification',json_encode($data));
    return "ok";
});
Route::get('/test', function () {
    $products = Thanatos\Modules\Inventory\Models\Products::search('a')->get();
    //$products->categories = $products->category;
    return json_encode($products);
});
