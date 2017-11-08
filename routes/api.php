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

		Route::patch('settings', 'AccountSettingController@update');
	});

	Route::prefix('arrangeables')->group(function () {
		Route::get('/', 'ArrangeableController@index');

		Route::get('settings', 'ArrangeableTypeSettingController@index');
		Route::patch('settings', 'ArrangeableTypeSettingController@update');

		Route::get('types', 'ArrangeableTypeController@index');
	});

	Route::prefix('arrangements')->group(function () {
		Route::prefix('{arrangement}')->group(function () {
			Route::patch('/', 'ArrangementProposalController@update');
			Route::delete('/', 'ArrangementProposalController@destroy');

			Route::prefix('discounts')->group(function () {
				Route::post('/', 'ArrangementDiscountController@store');
				Route::delete('{discount}', 'ArrangementDiscountController@destroy');
			});

			Route::prefix('ingredients')->group(function () {
				Route::get('/', 'ArrangementIngredientController@index');
		    	Route::post('/', 'ArrangementIngredientController@store');
				Route::patch('{ingredient}', 'ArrangementIngredientController@update');
			    Route::delete('{ingredient}', 'ArrangementIngredientController@destroy');
			});
		});
	});

	Route::prefix('contacts')->group(function () {
	    Route::get('/', 'ContactController@index');
		Route::post('/', 'ContactController@store');

		Route::prefix('{contact}')->group(function () {
			Route::get('/', 'ContactController@show');
			Route::patch('/', 'ContactController@update');

			Route::get('notes', 'NoteController@index');
			Route::post('notes', 'NoteController@store');
		});
	});

	Route::prefix('customers')->group(function () {
	    Route::get('/', 'CustomerController@index');
	    Route::post('/', 'CustomerController@store');

	    Route::prefix('{customer}')->group(function () {
			Route::get('/', 'CustomerController@show');
			Route::patch('/', 'CustomerController@update');

			Route::get('contacts', 'ContactCustomerController@index');

			Route::get('events', 'CustomerEventController@index');
			Route::post('events', 'CustomerEventController@store');

			Route::get('notes', 'NoteController@index');
			Route::post('notes', 'NoteController@store');
		});
	});

	Route::prefix('deliveries')->group(function () {
	    Route::patch('{delivery}', 'DeliveryController@update');
	});

	Route::prefix('events')->group(function () {
		Route::get('/', 'EventController@index');
		Route::post('/', 'EventController@store');

		Route::prefix('{event}')->group(function () {
			Route::patch('/', 'EventController@update');

			Route::get('notes', 'NoteController@index');
			Route::post('notes', 'NoteController@store');

			Route::prefix('proposals')->group(function () {
				Route::get('/', 'ProposalController@index');
				Route::post('/', 'ProposalController@store');
				Route::patch('{proposal}', 'ProposalController@update');
			});
		});
	});

	Route::prefix('flowers')->group(function () {
		Route::get('/', 'FlowerController@index');
		Route::post('/', 'FlowerController@store');

		Route::prefix('{flower}')->group(function () {
			Route::get('varieties', 'FlowerVarietyController@index');
			Route::post('varieties', 'FlowerVarietyController@store');

			Route::get('notes', 'NoteController@index');
			Route::post('notes', 'NoteController@store');
		});
	});

	Route::get('invitations', 'InviteController@index');

	Route::prefix('items')->group(function () {
		Route::get('/', 'ItemController@index');
		Route::post('/', 'ItemController@store');

		Route::prefix('{item}')->group(function () {
			Route::get('/', 'ItemController@show');
			Route::patch('/', 'ItemController@update');

			Route::get('notes', 'NoteController@index');
			Route::post('notes', 'NoteController@store');
		});
	});

	Route::prefix('markups')->group(function () {
		Route::get('/', 'MarkupController@index');
	});

	Route::prefix('notes')->group(function () {
		Route::delete('{note}', 'NoteController@destroy');
		Route::patch('{note}', 'NoteController@update');
	});

	Route::patch('password', 'UpdatePasswordController@update');

	Route::prefix('profile')->group(function () {
		Route::get('/', 'ProfileController@index');
		Route::patch('/', 'ProfileController@update');
	});

	Route::prefix('proposals')->group(function () {
		Route::prefix('{proposal}')->group(function () {
			Route::get('/', 'ProposalController@show');

			Route::get('arrangements', 'ArrangementProposalController@index');
			Route::post('arrangements', 'ArrangementProposalController@store');

			Route::get('deliveries', 'DeliveryProposalController@index');
			Route::post('deliveries', 'DeliveryProposalController@store');

			Route::get('setups', 'ProposalSetupController@index');
			Route::post('setups', 'ProposalSetupController@store');

			Route::get('vendors', 'ProposalVendorController@index');
			Route::post('vendors', 'ProposalVendorController@store');
			Route::delete('vendors/{vendor}', 'ProposalVendorController@destroy');
		});
	});

	Route::prefix('setups')->group(function () {
	    Route::patch('{setup}', 'SetupController@update');
	});

	Route::prefix('users')->group(function () {
	    Route::get('/', 'UserController@index');
		Route::post('/', 'InviteController@store');
	});

	Route::prefix('varieties')->group(function () {
		Route::prefix('{flower_variety}')->group(function () {
			Route::patch('/', 'FlowerVarietyController@update');
		    Route::get('sources', 'FlowerVarietySourceController@index');
		    Route::post('sources', 'FlowerVarietySourceController@store');
		});
	});

	Route::prefix('vendors')->group(function () {
		Route::get('/', 'VendorController@index');
		Route::post('/', 'VendorController@store');

		Route::prefix('{vendor}')->group(function () {
			Route::patch('/', 'VendorController@update');

			Route::get('notes', 'NoteController@index');
			Route::post('notes', 'NoteController@store');
		});
	});
});
