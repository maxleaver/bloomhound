<?php

use Faker\Generator as Faker;

$factory->define(App\FlowerLibrary::class, function (Faker $faker) {
    return [
        'type' => $faker->text(20),
        'name' => ucfirst($faker->word),
        'description' => $faker->sentence,
    ];
});
