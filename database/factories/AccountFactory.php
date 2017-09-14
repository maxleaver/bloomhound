<?php

use Faker\Generator as Faker;

$factory->define(App\Account::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'website' => $faker->url,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
    ];
});
