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
Route::get('config:cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config Clear';
});
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => ['auth'],'prefix'=>'admin'], function() {    
	Route::resource('dashboard', 'AdminController');
	
	Route::post('user/getall', 'UserController@getall');
    Route::post('user/getalls', 'UserController@getalls');
    Route::post('user/getallg', 'UserController@getallg');
    Route::get('user/service_providers', 'UserController@service_providers');
    Route::get('user/general_contractor', 'UserController@general_contractor');
    Route::resource('user', 'UserController');	
    
    Route::post('servicecategory/getall', 'Service_category@getall');
    Route::resource('servicecategory', 'Service_category');	
});
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout',function(){
	Auth::logout();
	return redirect('/login');
});
