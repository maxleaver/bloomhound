<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'name' => $faker->name,
        'email' => $faker->email,
    	'phone' => $faker->phoneNumber,
    	'address' => $faker->address,
    ];
});
