<?php

use Faker\Generator as Faker;

$factory->define(App\ArrangementIngredient::class, function (Faker $faker) {
    return [
        'arrangement_id' => function () {
        	return factory('App\Arrangement')->create()->id;
        },
        'arrangeable_id' => function (array $note) {
        	return factory('App\Item')->create()->id;
        },
        'arrangeable_type' => 'App\Item',
        'quantity' => $faker->randomNumber(2),
    ];
});
