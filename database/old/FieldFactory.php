<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Tychovbh\Mvc\Models\Form;
use Tychovbh\Mvc\Models\Element;

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
        'properties' => ['name' => $faker->name, 'required' => $faker->boolean],
        'form_id' => factory(Form::class)->create()->id,
        'element_id' => DB::table('elements')->inRandomOrder()->first()->id ?? factory(Element::class)->create()->id,
    ];
});
