<?php

use Faker\Generator as Faker;

$factory->define(App\Markup::class, function (Faker $faker) {
    return [
        'name' => $faker->text(25),
        'title' => $faker->text(25),
        'description' => $faker->text(25),
        'allow_entry' => true,
        'field_label' => 'test',
    ];
});
