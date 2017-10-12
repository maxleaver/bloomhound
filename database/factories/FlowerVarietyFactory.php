<?php

use Faker\Generator as Faker;

$factory->define(App\FlowerVariety::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'arrangeable_type_id' => function () {
            return factory('App\ArrangeableType')->create()->id;
        },
        'flower_id' => function () {
            return factory('App\Flower')->create()->id;
        },
        'markup_id' => function () {
            return \App\Markup::whereName('cost_plus_amount')->first()->id;
        },
        'markup_value' => $faker->randomDigitNotNull,
        'use_default_markup' => true,
        'name' => $faker->word(),
    ];
});
