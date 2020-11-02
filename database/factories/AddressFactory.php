<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */

use Faker\Generator as Faker;
use Tychovbh\Mvc\Address;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Address::class, function (Faker $faker) {
    return [
        'zipcode' => $faker->postcode,
        'house_number' => $faker->numberBetween(0, 100),
        'country' => $faker->country
    ];
});
