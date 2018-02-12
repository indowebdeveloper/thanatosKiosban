<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Geolocation API
\Igaster\LaravelCities\Geo::ApiRoutes();
// Reresh CSRF
Route::middleware('auth')->get('/refreshCsrf', function(){
    return json_encode(array(
      'token' => csrf_token()
    ));
});

// My details / current user details
Route::middleware('auth')->get('/my-permission', 'MyData@myPermission');
// User management routing
Route::middleware('auth')->get('/listUsers', 'UserManagement@listAjax');
Route::middleware('auth')->get('/all-users',function(){
  $users = \Thanatos\User::select(['id','name'])->get();
  return json_encode([
    'users' => $users
  ]);
});
Route::middleware('auth')->get('/getUser/{id}', 'UserManagement@getUser');
Route::middleware('auth')->post('/saveUser', 'UserManagement@saveUser');
Route::middleware('auth')->post('/removeUser', 'UserManagement@removeUser');
// Roles Management Routing
Route::middleware('auth')->get('/all-roles', 'RoleManagement@getAllRolesJSON');
Route::middleware('auth')->get('/all-permissions', 'RoleManagement@getAllPermissionsJSON');
Route::middleware('auth')->get('/listRoles', 'RoleManagement@listAjax');
Route::middleware('auth')->get('/getRole/{id}', 'RoleManagement@getRole');
Route::middleware('auth')->post('/saveRole', 'RoleManagement@saveRole');
Route::middleware('auth')->post('/removeRole', 'RoleManagement@removeRole');
// Settings Management Route
Route::middleware('auth')->get('/get-settings/{metaKey}', 'SettingsManagement@getSetting');
Route::middleware('auth')->post('/save-settings/{metaKey}', 'SettingsManagement@saveSetting');


Route::get('/test-email',function(){
  $data = [
      'logo' => '/assets/images/vendors/2.png'
  ];
  Illuminate\Support\Facades\Mail::to('dammionx@gmail.com')->send(new Thanatos\Mail\OrderCreatedNotifyAdmin($data));
});

Route::get('/test-snappy',function(){
  return SnappyImage::loadFile('http://www.github.com')
  ->setOption('disable-smart-width',true)
  ->setOption('width','700')
  ->inline();
});