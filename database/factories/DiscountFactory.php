<?php

use Faker\Generator as Faker;

$factory->define(App\Discount::class, function (Faker $faker) {
    return [
    	'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'name' => $faker->text(20),
        'type' => 'percent',
        'amount' => $faker->randomDigitNotNull,
        'discountable_id' => function (array $discount) {
            return factory('App\Arrangement')->create([
            	'account_id' => $discount['account_id']
            ]);
        },
        'discountable_type' => 'App\Arrangement',
    ];
});

$factory->state(App\Discount::class, 'fixed', [
    'type' => 'fixed',
]);

$factory->state(App\Discount::class, 'percent', [
    'type' => 'percent',
]);
