<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;

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

$factory->define(User::class, function (Faker\Generator $faker) {
    
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));
    
    $roles = ['intern','employee'];
    $i = array_rand($roles);
    return [
        'name' => $faker->name,
        'cpf' => $faker->unique()->cpf,
        'password' => bcrypt('123abc'),
        'role' => $roles[$i]
    ];
});