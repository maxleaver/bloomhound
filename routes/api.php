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
	Route::get('invitations', 'InviteController@index');

	Route::prefix('users')->group(function () {
	    Route::get('/', 'UserController@index');
		Route::post('/', 'InviteController@store');
	});

	Route::prefix('customers')->group(function () {
	    Route::get('/', 'CustomerController@index');
		Route::get('/{customer}', 'CustomerController@show');
		Route::get('/{customer}/contacts', 'ContactCustomerController@index');
		Route::post('/', 'CustomerController@store');
	});

	Route::prefix('contacts')->group(function () {
	    Route::get('/', 'ContactController@index');
		Route::post('/', 'ContactController@store');
	});

	Route::prefix('events')->group(function () {
		Route::get('/', 'EventController@index');
		Route::post('/', 'EventController@store');
	});

	Route::prefix('vendors')->group(function () {
		Route::get('/', 'VendorController@index');
		Route::post('/', 'VendorController@store');
	});
});