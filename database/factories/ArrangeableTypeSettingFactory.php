<?php

use Faker\Generator as Faker;

$factory->define(App\ArrangeableTypeSetting::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'arrangeable_type_id' => function () {
            return factory('App\ArrangeableType')->create()->id;
        },
        'markup_id' => function () {
            return factory('App\Markup')->create()->id;
        },
        'markup_value' => $faker->randomDigitNotNull,
    ];
});
