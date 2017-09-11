<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        }
    ];
});
