<?php

use Faker\Generator as Faker;

$factory->define(App\Flower::class, function (Faker $faker) {
    return [
    	'name' => $faker->text(25),
    	'account_id' => function () {
    		return factory('App\Account')->create()->id;
    	},
    	'flower_library_id' => function () {
            return factory('App\FlowerLibrary')->create()->id;
        },
    ];
});
