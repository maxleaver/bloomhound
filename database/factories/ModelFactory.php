<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
    	'account_id' => function () {
        	return factory('App\Account')->create()->id;
        },
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Account::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\Invite::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        }
    ];
});

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        }
    ];
});

