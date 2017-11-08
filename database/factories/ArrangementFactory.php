<?php

use Faker\Generator as Faker;

$factory->define(App\Arrangement::class, function (Faker $faker) {
    return [
        'account_id' => function () {
        	return factory('App\Account')->create()->id;
        },
        'proposal_id' => function (array $arrangement) {
            return factory('App\Proposal')->create([
                'event_id' => create('App\Event', [
                    'account_id' => $arrangement['account_id']
                ])
            ]);
        },
        'delivery_id' => null,
        'name' => $faker->text(20),
        'description' => $faker->text(20),
        'quantity' => $faker->randomNumber(2),
    ];
});

$factory->state(App\Arrangement::class, 'delivery', [
    'delivery_id' => function (array $arrangement) {
        return factory('App\Delivery')->create([
            'account_id' => $arrangement['account_id'],
            'proposal_id' => $arrangement['proposal_id'],
        ]);
    },
]);
