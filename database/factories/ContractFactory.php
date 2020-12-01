<?php

/**
 * @var Factory $factory
 */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Tychovbh\Mvc\Models\Contract;
use Tychovbh\Mvc\Models\User;

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
        'status' => $faker->randomElement(Contract::STATUSES),
        'signed_at' => $faker->dateTime()->format('Y-m-d H:i:s'),
        'user_id' =>  factory(User::Class)->create()->id
    ];
});
