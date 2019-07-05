<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Input;

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
        'required' => $faker->boolean ? '1' : '0',
        'form_id' => factory(Form::class)->create()->id,
        'input_id' => DB::table('inputs')->inRandomOrder()->first()->id ?? factory(Input::class)->create()->id,
    ];
});
