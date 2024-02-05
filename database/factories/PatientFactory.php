<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Patient;

$factory->define(Patient::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\pt_BR\Address($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Company($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Internet($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Payment($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\Person($faker));
    $faker->addProvider(new Faker\Provider\pt_BR\PhoneNumber($faker));
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'phone' => preg_replace('/\D/', '', $faker->phoneNumber),
        'profession' => $faker-> word(),
        'birthday' => $faker->date,
        'height' => $faker->randomFloat(3, 1.5, 2.0),
        'weight' => $faker->numberBetween(50, 120),
        'address' => $faker->address,
        'cpf'=> $faker->cpf,
        'cep'=> preg_replace('/\D/', '', $faker->postcode),
        'neighborhood' => $faker->streetName,
        'state' => $faker->stateAbbr,
        'extras' => ''
    ];
});
