<?php

use Faker\Generator as Faker;

$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'relationship' => $faker->sentence,
        'address' => $faker->address,
        'account_id' => function () {
            return factory('App\Account')->create()->id;
        },
        'customer_id' => function (array $contact) {
            return factory('App\Customer')->create(['account_id' => $contact['account_id']]);
        }
    ];
});
