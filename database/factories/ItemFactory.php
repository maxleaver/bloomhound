<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'arrangeable_type_id' => function () {
            return \App\ArrangeableType::whereName('hardgood')->first()->id;
        },
        'markup_id' => function () {
            return \App\Markup::whereName('cost_plus_amount')->first()->id;
        },
        'markup_value' => $faker->randomDigitNotNull,
        'use_default_markup' => true,
        'name' => $faker->text(25),
        'description' => $faker->sentence,
        'inventory' => 10,
        'cost' => $faker->randomDigitNotNull,
    ];
});

$factory->state(App\Item::class, 'cost', [
    'markup_id' => function () {
        return \App\Markup::whereName('cost')->first()->id;
    },
]);
