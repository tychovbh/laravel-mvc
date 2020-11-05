<?php

/**
 * @var Factory $factory
 */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Tychovbh\Mvc\Address;
use Tychovbh\Mvc\Country;

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
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'country_id' => factory(Country::class)->create()->id
    ];
});
