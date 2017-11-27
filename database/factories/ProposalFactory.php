<?php

use Faker\Generator as Faker;

$factory->define(App\Proposal::class, function (Faker $faker) {
    return [
        'event_id' => function () {
            return factory('App\Event')->create()->id;
        },
    ];
});
