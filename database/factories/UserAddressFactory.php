<?php

use App\UserAddress;
use Faker\Generator as Faker;

$factory->define(UserAddress::class, function (Faker $faker) {
    return [
        'address' => $faker->address,
        'is_main_address' => false
    ];
});
