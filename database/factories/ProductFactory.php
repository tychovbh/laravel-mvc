<?php

use Faker\Generator as Faker;

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

$factory->define(Tychovbh\Mvc\Product::class, function (Faker $faker) {
    return [
        'label' => $faker->name,
        'name' => uniqid(),
        'price' => $faker->randomFloat(2, 1, 1000),
        'tax_rate' => 21,
    ];
});
