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
    $users = App\User::all();

    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('carepw'),
        'remember_token' => str_random(10),
        'role' => 'caregiver',
        'rate' => $faker->randomElement($array = array('Mau', 'Normal', 'Bom', 'Muito Bom', 'Excelente')),
        'location' => $faker->randomElement($array = array('Lisboa', 'Porto', 'Leiria', 'Coimbra', 'Faro')),
        'created_by' => $users->random()->id,
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
    $caregivers = App\Caregiver::all();
    $healthcare_pros = App\HealthcarePro::all();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'location' => $faker->randomElement($array = array('Lisboa', 'Porto', 'Leiria', 'Coimbra', 'Faro')),
        'caregiver_id' => $caregivers->random()->id,
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Need::class, function (Faker\Generator $faker) {
    $array = array('Fazer Endoscopia', 'Mudar penso', 'Dar Comprimido', 'Mudar Sonda', 'Analisar Sangue');
    $healthcare_pros = App\HealthcarePro::all();

    return [
        'description' => $faker->randomElement($array),
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\TextFile::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => $faker->randomElement($array = array('Texto', 'Tutorial', 'FAQ')) . ' - ' . $faker->randomNumber,
        'description' => $faker->randomElement($array = array('Texto', 'Tutorial', 'FAQ')) . ' de ' . $faker->name,
        'type' => 'textFile',
        'path' => 'C:\\',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Image::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => 'Imagem ' . $faker->randomNumber,
        'description' => 'Imagem de ' . $faker->name,
        'type' => 'image',
        'path' => $faker->imageUrl($width = 304, $height = 228),
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Video::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => $faker->unique()->randomElement($array = array('Video - Mudar Penso 1', 'Video - Fazer Endoscopia 1', 'Video 2', 'Video 3', 'Video 4')),
        'description' => $faker->randomElement($array = array('Video sobre como mudar o penso', 'Video sobre como fazer endoscopia')),
        'type' => 'video',
        'url' => $faker->randomElement($array = array('https://www.youtube.com/watch?v=-vSXINtEPpE', 'https://www.youtube.com/watch?v=RoXmMD1rVP0')),
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\EmergencyContact::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => 'Contacto de Emergência ' . $faker->randomNumber,
        'description' => 'Contacto de Emergência de ' . $faker->name,
        'type' => 'emergencyContact',
        'number' => $faker->phoneNumber,
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Composite::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => 'Composto ' . $faker->randomNumber,
        'description' => 'Composto de ' . $faker->name,
        'type' => 'composite',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Proceeding::class, function (Faker\Generator $faker) {
    $patient = App\Patient::all()->random();
    $need = $patient->needs->random();
    $material = $need->materials->random();

    return [
        'note' => $faker->name,
        'caregiver_id' => $patient->caregiver_id,
        'material_id' => $material->id,
        'patient_id' => $patient->id,
    ];
});

$factory->define(App\Log::class, function (Faker\Generator $faker) {
    $user = App\User::all()->random();

    return [
        'performed_task' => $faker->randomElement($array = array('Criou', 'Atualizou', 'Removeu')) . ' um ' .  $faker->randomElement($array = array('Patiente', 'Material', 'Cuidador', 'Profissional de Saúde')),
        'user_id' => $user->id,
    ];
});