<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tychovbh\Mvc\Models\Form;

class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label' => $this->faker->name,
            'name' => uniqid(),
            'description' => $this->faker->sentence,
        ];
    }
}
