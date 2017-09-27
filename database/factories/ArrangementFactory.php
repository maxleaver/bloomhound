<?php

use Faker\Generator as Faker;

$factory->define(App\Arrangement::class, function (Faker $faker) {
    return [
        'account_id' => function () {
        	return factory('App\Account')->create()->id;
        },
        'name' => $faker->text(20),
    ];
});
