<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Report;
use Faker\Generator as Faker;

$factory->define(Report::class, function (Faker $faker) {
    return [
        'patient_id' => factory(\App\Patient::class),
        'mnemonic' => $faker->randomLetter
    ];
});
