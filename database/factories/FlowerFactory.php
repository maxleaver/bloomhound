<?php

use Faker\Generator as Faker;

$factory->define(App\Flower::class, function (Faker $faker) {
    return [
    	'account_id' => function () {
    		return factory('App\Account')->create()->id;
    	},
    	'flower_library_id' => function () {
            return factory('App\FlowerLibrary')->create()->id;
        },
        'name' => $faker->text(25),
    ];
});
