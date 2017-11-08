<?php

use App\EventStatus;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Event::class, function (Faker $faker) {
    return [
        'name' => $faker->text(25),
        'date' => Carbon::now(),
        'status_id' => function () {
            return factory('App\EventStatus')->create()->id;
        },
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'customer_id' => function (array $contact) {
            return factory('App\Customer')->create([
                'account_id' => $contact['account_id']
            ]);
        },
    ];
});
