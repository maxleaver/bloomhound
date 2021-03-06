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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::prefix('account')->group(function () {
        Route::get('settings', 'AccountSettingsController@index')->name('account.settings');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', 'ContactController@index')->name('contacts.index');
        Route::get('{contact}', 'ContactController@show')->name('contacts.show');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', 'CustomerController@index')->name('customers.index');
        Route::get('{customer}', 'CustomerController@show')->name('customers.show');
    });

    Route::prefix('events')->group(function () {
        Route::get('/', 'EventController@index')->name('events.index');
        Route::get('{event}', 'EventController@show')->name('events.show');
    });

    Route::prefix('flowers')->group(function () {
        Route::get('/', 'FlowerController@index')->name('flowers.index');
        Route::get('{flower}', 'FlowerController@show')->name('flowers.show');
    });

    Route::prefix('items')->group(function () {
        Route::get('/', 'ItemController@index')->name('items.index');
        Route::get('{item}', 'ItemController@show')->name('items.show');
    });

    Route::prefix('my')->group(function () {
        Route::get('profile', 'ProfileController@index')->name('my.profile');
    });

    Route::prefix('vendors')->group(function () {
        Route::get('/', 'VendorController@index')->name('vendors.index');
        Route::get('{vendor}', 'VendorController@show')->name('vendors.show');
    });
});
