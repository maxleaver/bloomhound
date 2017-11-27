<?php

use Faker\Generator as Faker;

$factory->define(App\Note::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'notable_id' => function (array $note) {
            return factory('App\Customer')->create()->id;
        },
        'notable_type' => 'App\Customer',
        'text' => $faker->paragraph,
    ];
});
