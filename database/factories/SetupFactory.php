<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Setup::class, function (Faker $faker) {
    return [
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'event_id' => function (array $delivery) {
            return factory('App\Event')->create([
            	'account_id' => $delivery['account_id']
            ]);
        },
        'address' => $faker->address,
        'setup_on' => Carbon::now()->addWeeks(2),
        'description' => $faker->text(25),
        'fee' => $faker->randomDigitNotNull
    ];
});
