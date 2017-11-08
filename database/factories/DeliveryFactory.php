<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Delivery::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'proposal_id' => function (array $delivery) {
            return factory('App\Proposal')->create([
                'event_id' => create('App\Event', [
                    'account_id' => $delivery['account_id']
                ])
            ]);
        },
        'address' => $faker->address,
        'deliver_on' => Carbon::now()->addWeeks(2),
        'description' => $faker->text(25),
        'fee' => $faker->randomDigitNotNull
    ];
});
