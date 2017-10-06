<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'item_type_id' => function () {
        	return factory('App\ItemType')->create()->id;
        },
    	'name' => $faker->text(25),
    	'description' => $faker->sentence,
    	'inventory' => 10,
    ];
});
