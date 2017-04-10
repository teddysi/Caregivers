<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Caregiver::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('carepw'),
        'remember_token' => str_random(10),
        'role' => 'caregiver',
        'rate' => $faker->randomElement($array = array('Mau', 'Normal', 'Bom', 'Muito Bom', 'Excelente')),
    ];
});

$factory->define(App\Admin::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('adminpw'),
        'remember_token' => str_random(10),
        'role' => 'admin',
    ];
});

$factory->define(App\HealthcarePro::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('propw'),
        'remember_token' => str_random(10),
        'role' => 'healthcarepro',
        'facility' => $faker->company,
        'job' => $faker->randomElement($array = array('Médico Geral', 'Médico de Família', 'Enfermeiro')),
    ];
});

$factory->define(App\Patient::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});