<?php

use Faker\Generator as Faker;

$factory->define(App\Invite::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        }
    ];
});