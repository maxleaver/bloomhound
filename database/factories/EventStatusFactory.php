<?php

use Faker\Generator as Faker;

$factory->define(App\EventStatus::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'title' => $faker->word,
    ];
});

