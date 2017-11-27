<?php

use Faker\Generator as Faker;

$factory->define(App\ArrangeableType::class, function (Faker $faker) {
    return [
        'default_markup_id' => \App\Markup::whereName('no_charge')->first()->id,
        'name' => $faker->text(25),
        'title' => $faker->text(25),
        'model' => 'item',
    ];
});
