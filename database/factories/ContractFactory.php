<?php

/**
 * @var Factory $factory
 */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Tychovbh\Mvc\Contract;

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

$factory->define(Contract::class, function (Faker $faker) {
    return [
        'file' => 'file.' . $faker->fileExtension,
        'status' => $faker->randomElement([
            Contract::STATUS_CONCEPT,
            Contract::STATUS_SENT,
            Contract::STATUS_SIGNED,
            Contract::STATUS_DENIED,
        ]),
        'signed_at' => $faker->dateTime()->getTimestamp()
    ];
});
