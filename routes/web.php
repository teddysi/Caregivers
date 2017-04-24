<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*********ADMIN*********/
Auth::routes();

Route::get('/', 'UserController@dashboard');

Route::get('/all_users', 'UserController@allUsers');

Route::get('/admins', 'UserController@admins');
	
Route::get('/healthcarepros', 'UserController@healthcarepros');

Route::get('/caregivers', [
	'as' => 'admin.admin_caregivers',
	'uses' => 'UserController@caregivers',
	'middleware' => 'auth'
]);

Route::get('/patients', 'PatientsController@patients');

Route::get('/needs', 'NeedController@needs');

Route::group(['middleware' => 'auth', 'prefix' => 'users'], function () {
	// falta fazer index, edit e block
    Route::get('/', [
		'as' => 'users',
		'uses' =>'UserController@index'
	]);
	Route::post('/', 'UserController@index');
	
	Route::get('create/{role}', [
		'as' => 'users.create',
		'uses' =>'UserController@create'
	]);
	Route::post('create', 'UserController@store');

	Route::get('{user}', [
		'as' => 'users.show',
		'uses' =>'UserController@show'
	]);

	Route::get('{user}/edit', [
		'as' => 'users.edit',
		'uses' =>'UserController@edit'
	]);
	Route::patch('{user}', 'UserController@update');

	Route::post('{user}/toggleBlock', [
		'as' => 'users.toggleBlock',
		'uses' =>'UserController@toggleBlock'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'materials'], function () {
    Route::get('/', [
		'as' => 'materials',
		'uses' =>'MaterialsController@index'
	]);
	Route::post('/', 'MaterialsController@index');
	
	Route::get('create/{type}', [
		'as' => 'materials.create',
		'uses' =>'MaterialsController@create'
	]);
	Route::post('create', 'MaterialsController@store');

	Route::get('{material}', [
		'as' => 'materials.show',
		'uses' =>'MaterialsController@show'
	]);

	Route::get('{material}/edit', [
		'as' => 'materials.edit',
		'uses' =>'MaterialsController@edit'
	]);
	Route::patch('{material}', 'MaterialsController@update');

	Route::post('{material}/toggleBlock', [
		'as' => 'materials.toggleBlock',
		'uses' =>'MaterialsController@toggleBlock'
	]);
});

Route::get('/healthcarepro{id}/caregivers', [
	'as' => 'admin.admin_healthcarepro_caregivers',
	'uses' =>'UserController@healthcareproCaregivers'
]);

Route::get('/caregiver{id}/patients', [
	'as' => 'admin.admin_caregiver_patients',
	'uses' =>'UserController@caregiverPatients'
]);

Route::get('/patient{id}/needs', [
	'as' => 'admin.admin_patient_needs',
	'uses' =>'PatientsController@patientNeeds'
]);

Route::get('/need{id}/materials', [
	'as' => 'admin.admin_need_materials',
	'uses' =>'NeedController@needMaterials'
]);

Route::get('/patients/create/', [
	'as' => 'create_patients',
	'uses' =>'PatientsController@createPatient'
]);

Route::post('/patients/save_patient', 'PatientsController@savePatient');

Route::get('/patients/update_patient/{id}', [
	'as' => 'patients.update_patient',
	'uses' =>'PatientsController@updatePatient'
]);

Route::post('/patients/update/{id}', [          
        'as' => 'patients.update',
        'uses' => 'PatientsController@update',
]);


//------------------------------------------------

Route::get('/needs/create/', [
	'as' => 'needs.create_need',
	'uses' =>'NeedController@createNeed'
]);

Route::post('/needs/save_need', 'NeedController@saveNeed');

// Caregivers API
Route::post('/caregivers/login', 'CaregiversController@login');
Route::get('/caregivers/{caregiver}/patients', 'CaregiversController@patients');
Route::get('/caregivers/{caregiver}/materials', 'CaregiversController@caregiversMaterials');
Route::get('/caregivers/{caregiver}/patients/{patient}/needs', 'CaregiversController@patientsNeeds');
Route::get('/caregivers/{caregiver}/patients/{patient}/materials', 'CaregiversController@patientsMaterials');
Route::get('/caregivers/{caregiver}/patients/{patient}/needs/{need}/materials', 'CaregiversController@patientsNeedsMaterials');
Route::get('/caregivers/{caregiver}/proceedings', 'CaregiversController@proceedings');

Route::post('/proceedings/create', 'ProceedingsController@create');
Route::patch('/proceedings/{proceeding}', 'ProceedingsController@update');

