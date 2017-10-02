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
	Route::prefix('account')->group(function () {
		Route::patch('/', 'AccountProfileController@update');
		Route::post('logo', 'AccountLogoController@store');
	});

	Route::get('/arrangeables', 'ArrangeableController@index');

	Route::prefix('arrangements')->group(function () {
		Route::prefix('{arrangement}')->group(function () {
			Route::delete('/', 'ArrangementEventController@destroy');

			Route::get('ingredients', 'ArrangementIngredientController@index');
		    Route::post('ingredients', 'ArrangementIngredientController@store');
		    Route::delete('ingredients/{ingredient}', 'ArrangementIngredientController@destroy');
		});
	});

	Route::prefix('customers')->group(function () {
	    Route::get('/', 'CustomerController@index');
		Route::get('{customer}', 'CustomerController@show');
		Route::get('{customer}/contacts', 'ContactCustomerController@index');
		Route::post('/', 'CustomerController@store');

		Route::get('{customer}/events', 'CustomerEventController@index');
		Route::post('{customer}/events', 'CustomerEventController@store');

		Route::get('/{customer}/notes', 'NoteController@index');
		Route::post('/{customer}/notes', 'NoteController@store');
	});

	Route::prefix('contacts')->group(function () {
	    Route::get('/', 'ContactController@index');
	    Route::get('{contact}', 'ContactController@show');
		Route::post('/', 'ContactController@store');

		Route::get('{contact}/notes', 'NoteController@index');
		Route::post('{contact}/notes', 'NoteController@store');
	});

	Route::prefix('events')->group(function () {
		Route::get('/', 'EventController@index');
		Route::post('/', 'EventController@store');

		Route::get('{event}/arrangements', 'ArrangementEventController@index');
		Route::post('{event}/arrangements', 'ArrangementEventController@store');

		Route::get('{event}/notes', 'NoteController@index');
		Route::post('{event}/notes', 'NoteController@store');
	});

	Route::prefix('flowers')->group(function () {
		Route::get('/', 'FlowerController@index');
		Route::post('/', 'FlowerController@store');

		Route::get('{flower}/varieties', 'FlowerVarietyController@index');
		Route::post('{flower}/varieties', 'FlowerVarietyController@store');

		Route::get('{flower}/notes', 'NoteController@index');
		Route::post('{flower}/notes', 'NoteController@store');
	});

	Route::get('invitations', 'InviteController@index');

	Route::prefix('items')->group(function () {
		Route::get('/', 'ItemController@index');
		Route::post('/', 'ItemController@store');

		Route::get('/{item}', 'ItemController@show');

		Route::get('{item}/notes', 'NoteController@index');
		Route::post('{item}/notes', 'NoteController@store');
	});

	Route::prefix('notes')->group(function () {
		Route::delete('/{note}', 'NoteController@destroy');
		Route::patch('/{note}', 'NoteController@update');
	});

	Route::patch('password', 'UpdatePasswordController@update');

	Route::prefix('profile')->group(function () {
		Route::get('/', 'ProfileController@index');
		Route::patch('/', 'ProfileController@update');
	});

	Route::prefix('users')->group(function () {
	    Route::get('/', 'UserController@index');
		Route::post('/', 'InviteController@store');
	});

	Route::prefix('varieties')->group(function () {
	    Route::get('{flower_variety}/sources', 'FlowerVarietySourceController@index');
	    Route::post('{flower_variety}/sources', 'FlowerVarietySourceController@store');
	});

	Route::prefix('vendors')->group(function () {
		Route::get('/', 'VendorController@index');
		Route::post('/', 'VendorController@store');

		Route::get('{vendor}/notes', 'NoteController@index');
		Route::post('{vendor}/notes', 'NoteController@store');
	});
});
