<?php

use Faker\Generator as Faker;

$factory->define(App\AccountSetting::class, function (Faker $faker) {
    return [
    	'account_id' => function () {
        	return factory('App\Account')->create()->id;
        },
        'use_tax' => false,
        'tax_amount' => null,
    ];
});
