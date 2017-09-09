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

Route::post('invitation/accept/{invite}', 'InviteController@accept')->name('invite');

Route::middleware('auth:api')->group(function () {
	Route::get('users', 'UserController@index');
	Route::post('users', 'InviteController@store');

	Route::get('invitations', 'InviteController@index');

	Route::get('customers', 'CustomerController@index');
	Route::get('customers/{customer}', 'CustomerController@show');
	Route::post('customers', 'CustomerController@store');

	Route::post('contacts', 'ContactController@store');
});