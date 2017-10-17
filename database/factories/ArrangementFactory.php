<?php

use Faker\Generator as Faker;

$factory->define(App\Arrangement::class, function (Faker $faker) {
    return [
        'account_id' => function () {
        	return factory('App\Account')->create()->id;
        },
        'event_id' => function (array $arrangement) {
            return factory('App\Event')->create(['account_id' => $arrangement['account_id']]);
        },
        'name' => $faker->text(20),
        'description' => $faker->text(20),
        'quantity' => $faker->randomNumber(2),
    ];
});
