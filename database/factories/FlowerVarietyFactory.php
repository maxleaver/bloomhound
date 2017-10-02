<?php

use Faker\Generator as Faker;

$factory->define(App\FlowerVariety::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'flower_id' => function () {
            return factory('App\Flower')->create()->id;
        },
        'name' => $faker->word(),
    ];
});
