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

$factory->define(Tychovbh\Mvc\Payment::class, function (Faker $faker) {
    return [
        'user_id' => factory(\Tychovbh\Mvc\User::class)->create()->id,
        'amount' => $faker->randomFloat(2, 1, 1000),
        'description' => $faker->word,
        'options' => [
            'page' => 'http://localhost:3000/page'
        ]
    ];
});
