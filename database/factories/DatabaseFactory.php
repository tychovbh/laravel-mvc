<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
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
        $name = 'testing_' . uniqid();

        $database = [
            'name' => $name,
            'label' => Str::ucfirst($name),
            'host' => '192.168.10.10',
            'username' => 'homestead',
            'password' => 'secret',
            'driver' => Database::DRIVER_MYSQL,
            'user_id' => User::factory()->create()->id
        ];

        config(['database.connections.' . $name => array_merge($database, ['database' => $name])]);

        $this->createDatabase($database);

        return $database;
    }

    /**
     * Create a database with tables to work with
     * @param array $database
     */
    public function createDatabase(array $database)
    {
        $connection = DB::connection('testing');
        $connection->statement('CREATE DATABASE ' . $database['name']);
        $connection = DB::connection($database['name']);

        $connection->statement('CREATE TABLE countries (
            id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            label varchar(255),
            continent enum("europe", "africa", "america", "asia", "australia") COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "europe",
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL
        )');

        $connection->statement('CREATE TABLE sports (
            id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            label varchar(255),
            country_id int unsigned DEFAULT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            KEY `sports_country_id_foreign` (`country_id`),
            CONSTRAINT `sports_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
        )');

        $connection->statement('CREATE TABLE users (
            id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL UNIQUE,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL
        )');

        $connection->statement('CREATE TABLE user_sports (
            id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
            user_id int unsigned DEFAULT NULL,
            sport_id int unsigned DEFAULT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            KEY `user_sports_user_id_foreign` (`user_id`),
            CONSTRAINT `user_sports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
            KEY `user_sports_sport_id_foreign` (`sport_id`),
            CONSTRAINT `user_sports_sport_id_foreign` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE
        )');
    }
}
