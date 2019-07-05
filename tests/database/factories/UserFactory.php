<?php

use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

$factory->define(\Tychovbh\Tests\Mvc\App\TestUser::class, function (Faker $faker) {
    Storage::fake('app/public/avatars');
    $file = UploadedFile::fake()->image('fake_photo.jpg');

    return [
        'password' => Hash::make(Str::random(10)),
        'email' => $faker->unique()->safeEmail,
        'avatar' => str_replace('public/', '', $file->store('avatars'))
    ];
});
