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

$factory->define(Tychovbh\Mvc\Token::class, function (Faker $faker) {
    return [
        'reference' => uniqid(),
        'value' => token([
            'email' => $faker->email,
        ]),
        'type' => \Tychovbh\Mvc\TokenType::INVITE_USER
    ];
});
