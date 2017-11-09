<?php

use Faker\Generator as Faker;

$factory->define(App\Discount::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'type' => 'percent',
        'amount' => $faker->randomDigitNotNull,
        'discountable_id' => function (array $discount) {
            return factory('App\Arrangement')->create();
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

$factory->state(App\Discount::class, 'proposal', [
    'discountable_id' => function (array $discount) {
        return factory('App\Proposal')->create();
    },
    'discountable_type' => 'App\Proposal',
]);
