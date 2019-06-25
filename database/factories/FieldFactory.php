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

$factory->define(Tychovbh\Mvc\Field::class, function (Faker $faker) {
    return [
        'label' => $faker->name,
        'name' => $faker->name,
        'description' => $faker->sentence,
        'placeholder' => $faker->word,
        'required' => $faker->boolean,
        'form_id' => factory(\Tychovbh\Mvc\Form::class)->create()->id,
        'input_id' => factory(\Tychovbh\Mvc\Input::class)->create()->id,
    ];
});
