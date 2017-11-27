<?php

use Faker\Generator as Faker;

$factory->define(App\FlowerVarietySource::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'flower_variety_id' => function () {
            return factory('App\FlowerVariety')->create()->id;
        },
        'vendor_id' => function (array $source) {
            return factory('App\Vendor')->create(['account_id' => $source['account_id']]);
        },
        'cost' => $faker->randomFloat(2),
        'stems_per_bunch' => $faker->randomDigitNotNull,
    ];
});
