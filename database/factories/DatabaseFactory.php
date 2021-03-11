<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Models\Database;
use Tychovbh\Mvc\Models\User;

class DatabaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Database::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = 'wielerflits';
        return [
            'name' => $name,
            'label' => Str::ucfirst($name),
            'host' => '192.168.10.10',
            'username' => 'homestead',
            'password' => 'secret',
            'driver' => Database::DRIVER_MYSQL,
            'user_id' => User::factory()->create()->id
        ];
    }
}
