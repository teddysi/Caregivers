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
    $users = App\User::where('role', '<>', 'caregiver')->get();

    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('carepw'),
        'role' => 'caregiver',
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
        'role' => 'healthcarepro',
        'facility' => $faker->company,
        'job' => $faker->randomElement($array = array('Médico Geral', 'Médico de Família', 'Enfermeiro')),
    ];
});

$factory->define(App\Patient::class, function (Faker\Generator $faker) {
    $countPatients = count(App\Patient::all());
    $caregivers = App\Caregiver::all();
    $healthcare_pros = App\HealthcarePro::all();

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'location' => $faker->randomElement($array = array('Lisboa', 'Porto', 'Leiria', 'Coimbra', 'Faro')),
        'caregiver_id' => $countPatients == 9 ? 15 : $caregivers->random()->id,
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Need::class, function (Faker\Generator $faker) {
    $array = array('Fazer Endoscopia', 'Mudar penso', 'Dar Comprimido', 'Mudar Sonda', 'Analisar Sangue', 'Acamado', 'Paralisado', 'Dermatologia', 'Cardiologia');
    $healthcare_pros = App\HealthcarePro::all();

    return [
        'description' => $faker->randomElement($array),
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Text::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => $faker->randomElement($array = array('Texto', 'Tutorial')) . ' - ' . $faker->unique()->randomNumber,
        'description' => $faker->randomElement($array = array('Texto', 'Tutorial')) . ' de ' . $faker->name,
        'type' => 'text',
        'body' => 'É um facto estabelecido de que um leitor é distraído pelo conteúdo legível de uma página quando analisa a sua mancha gráfica. Logo, o uso de Lorem Ipsum leva a uma distribuição mais ou menos normal de letras, ao contrário do uso de "Conteúdo aqui, conteúdo aqui", tornando-o texto legível. Muitas ferramentas de publicação electrónica e editores de páginas web usam actualmente o Lorem Ipsum como o modelo de texto usado por omissão, e uma pesquisa por "lorem ipsum" irá encontrar muitos websites ainda na sua infância. Várias versões têm evoluído ao longo dos anos, por vezes por acidente, por vezes propositadamente (como no caso do humor).',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Image::class, function (Faker\Generator $faker) {
    $countMaterials = count(App\Material::all());
    $healthcare_pros = App\HealthcarePro::all();
    $rndNumber = $faker->unique()->randomNumber;
    
    return [
        'name' => 'Imagem ' . $rndNumber,
        'description' => 'Imagem de ' . $faker->name,
        'type' => 'image',
        'url' => 'http://192.168.99.100/healthmanagement/public/materials/'.($countMaterials+1).'/showContent',
        'path' => 'images/Imagem ' . $rndNumber,
        'mime' => '.jpg',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Video::class, function (Faker\Generator $faker) {
    $countMaterials = count(App\Material::all());
    $healthcare_pros = App\HealthcarePro::all();
    $rndNumber = $faker->unique()->randomNumber;
    
    return [
        'name' => 'Video ' . $rndNumber,
        'description' => 'Video de ' . $faker->name,
        'type' => 'video',
        'url' => 'http://192.168.99.100/healthmanagement/public/materials/'.($countMaterials+1).'/showContent',
        'path' => 'videos/'.'Video ' . $rndNumber,
        'mime' => '.mp4',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\EmergencyContact::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => 'Contacto de Emergência ' . $faker->unique()->randomNumber,
        'description' => 'Contacto de Emergência de ' . $faker->name,
        'type' => 'emergencyContact',
        'number' => $faker->phoneNumber,
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Annex::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => 'Anexo ' . $faker->unique()->randomNumber,
        'description' => 'Anexo de ' . $faker->name,
        'type' => 'annex',
        'url' => 'https://www.youtube.com/watch?v=RoXmMD1rVP0',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Composite::class, function (Faker\Generator $faker) {
    $healthcare_pros = App\HealthcarePro::all();
    
    return [
        'name' => 'Composto ' . $faker->unique()->randomNumber,
        'description' => 'Composto de ' . $faker->name,
        'type' => 'composite',
        'created_by' => $healthcare_pros->random()->id,
    ];
});

$factory->define(App\Access::class, function (Faker\Generator $faker) {
    $patient = App\Patient::all()->random();
    $need = $patient->needs->random();
    $material = $need->materials->random();

    return [
        'caregiver_id' => $patient->caregiver_id,
        'material_id' => $material->id,
        'patient_id' => $patient->id,
    ];
});

$factory->define(App\Log::class, function (Faker\Generator $faker) {
    $doneBy = App\User::where('role', '<>', 'caregiver')->get()->random();
    $user = App\User::all()->random();
    $patient = App\Patient::all()->random();
    $need = App\Need::all()->random();
    $material = App\Material::all()->random();
    $evaluation = App\Evaluation::all()->random();
    $rand = rand(0, 25);

    return [
        'performed_task' => $faker->randomElement($array = array('Foi criado.', 'Foi atualizado.', 'Foi bloqueado.', 'Foi desbloqueado.')),
        'done_by' => $doneBy->id,
        'user_id' => $rand < 5 ? $user->id : null,
        'patient_id' => ($rand >= 5 && $rand < 10) ? $patient->id : null,
        'need_id' => ($rand >= 10 && $rand < 15) ? $need->id : null,
        'material_id' => ($rand >= 15 && $rand < 20) ? $material->id : null,
        'evaluation_id' => ($rand >= 20 && $rand < 25) ? $evaluation->id : null,
    ];
});