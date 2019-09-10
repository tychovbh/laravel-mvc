<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tychovbh\Mvc\PasswordReset;
use Tychovbh\Mvc\User;

$factory->define(PasswordReset::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    return [
        'email' => $user->email,
    ];
});
