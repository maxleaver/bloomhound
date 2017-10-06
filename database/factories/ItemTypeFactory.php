<?php

use Faker\Generator as Faker;

$factory->define(App\ItemType::class, function (Faker $faker) {
    return [
    	'name' => $faker->text(25),
    	'title' => $faker->text(25),
    ];
});
