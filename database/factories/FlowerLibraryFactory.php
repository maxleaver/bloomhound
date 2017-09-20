<?php

use Faker\Generator as Faker;

$factory->define(App\FlowerLibrary::class, function (Faker $faker) {
    return [
    	'type' => $faker->word,
    	'name' => ucfirst($faker->word),
    	'description' => $faker->sentence,
    ];
});
