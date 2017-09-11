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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
	Route::get('/home', 'HomeController@index')->name('home');

	// Route::get('users', 'UserController@index');
	// Route::post('users', 'InviteController@store');

	// Route::get('invitations', 'InviteController@index');

	Route::prefix('customers')->group(function () {
	    Route::get('/', 'CustomerController@index')->name('customers.index');
	    Route::post('/', 'CustomerController@store')->name('customers.store');
		Route::get('/{customer}', 'CustomerController@show')->name('customers.show');
	});

	Route::prefix('contacts')->group(function () {
	    Route::get('/', 'ContactController@index')->name('contacts.index');
	});

	// Route::post('contacts', 'ContactController@store');
});
