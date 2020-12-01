<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'is_admin' => 0,
        'password' => Hash::make($faker->password),
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s')
    ];
});
