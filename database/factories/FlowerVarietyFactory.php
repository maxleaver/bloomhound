<?php

use Faker\Generator as Faker;

$factory->define(App\FlowerVariety::class, function (Faker $faker) {
    return [
    	'flower_id' => function () {
            return factory('App\Flower')->create()->id;
        },
        'name' => $faker->word,
    ];
});
