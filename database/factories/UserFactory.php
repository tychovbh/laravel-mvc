<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

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

$factory->define(\Tychovbh\Tests\Mvc\App\TestUser::class, function (Faker $faker) {
    return [
        'password' => Hash::make(str_random(10)),
        'email' => $faker->unique()->safeEmail,
        'firstname' => $faker->name,
        'surname' => $faker->name,
        'hidden' => 0,
    ];
});
