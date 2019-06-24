<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

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

$factory->define(Tychovbh\Mvc\Input::class, function (Faker $faker) {
    $randInput = DB::table('inputs')->inRandomOrder()->first();
    return [
        'label' => $randInput->label,
        'name' => $randInput->name,
        'description' => $randInput->description,
    ];
});
