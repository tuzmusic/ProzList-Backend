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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1')->group(function () {
	Route::post('registration', 'APIV1Controller@registration');
	Route::post('login', 'APIV1Controller@login');
	Route::post('forgot-password', 'APIV1Controller@forgot_password');
	Route::get('getprofile/{id}', 'APIV1Controller@get_profile');
        //Route::put('update_profile/{id}', 'APIV1Controller@update_profile');
	Route::post('update_profile/{id}', 'APIV1Controller@update_profile');
        //Get Service category/Type list
	Route::get('getsercat', 'APIV1Controller@get_service_category');
	Route::post('create_request', 'APIV1Controller@create_request');
	Route::get('get_service_request/{id}/{type}', 'APIV1Controller@get_service_request');
	//Route::post('registration', 'APIV1Controller@registration');
	Route::post('upload_certificate', 'APIV1Controller@upload_certificate');
	Route::post('subscription_save', 'APIV1Controller@subscription_save');
	Route::post('save_request_status', 'APIV1Controller@save_request_status');
	Route::post('give_rating', 'APIV1Controller@give_rating');
	
	Route::post('get_nearbyjob', 'APIV1Controller@get_nearby_job');
	Route::get('get_current_request/{user_id}/{type}', 'APIV1Controller@get_current_request');

	Route::get('get_my_review/{user_id}/{role}', 'APIV1Controller@get_my_review');
	Route::get('get_strike/{user_id}', 'APIV1Controller@get_strike');
	Route::get('get_award/{user_id}', 'APIV1Controller@get_award');
	Route::post('update_location', 'APIV1Controller@updatelocation');
	

	
});
