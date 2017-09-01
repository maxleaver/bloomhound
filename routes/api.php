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

Route::post('register', 'Auth\RegisterController@register');
Route::post('auth', 'Auth\LoginController@login');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

// Route::get('/login', 'Auth\LoginController@login');
// Route::get('/login/refresh', 'Auth\LoginController@refresh');

// Route::middleware('auth:api')->group(function () {
// 	Route::get('/logout', 'Auth\LoginController@logout');

// 	Route::get('/user', function (Request $request) {
// 	    return $request->user();
// 	});
// });