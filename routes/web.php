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
Route::get('/', 'UserController@dashboard');

Route::get('/all_users', 'UserController@allUsers');

Route::get('/admins', 'UserController@admins');
	
Route::get('/healthcarepros', 'UserController@healthcarepros');

Route::get('/caregivers', [
	'as' => 'admin.admin_caregivers',
	'uses' => 'UserController@caregivers'
	]);

Route::get('/patients', 'UserController@patients');

Route::get('/needs', 'NeedController@needs');

Route::get('/materials', 'MaterialController@materials');

Route::get('/user{id}/details', [
	'as' => 'admin.admin_user_details',
	'uses' =>'UserController@details'
	]);

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
	'uses' =>'UserController@patientNeeds'
	]);

Route::get('/need{id}/materials', [
	'as' => 'admin.admin_need_materials',
	'uses' =>'UserController@needMaterials'
	]);

Route::get('/users/create/{role}', [
	'as' => 'create_user',
	'uses' =>'UserController@createUser'
	]);

Route::post('/users/create_admin', 'UserController@saveAdmin');
Route::post('/users/create_healthcarepro', 'UserController@saveHealthcarepro');
Route::post('/users/create_caregiver', 'UserController@saveCaregiver');


Route::post('users/deleteHealthcarepro/{id}', [              
        'as' => 'users.deleteHealthcarepro',
        'uses' => 'UserController@deleteHealthcarepro',
    ]);

Route::post('users/deleteAdmin/{id}', [              
        'as' => 'users.deleteAdmin',
        'uses' => 'UserController@deleteAdmin',
    ]);

Route::post('users/deleteCaregiver/{id}', [              
        'as' => 'users.deleteCaregiver',
        'uses' => 'UserController@deleteCaregiver',
    ]);

Route::get('/users/update/{id}', [
	'as' => 'update_user',
	'uses' =>'UserController@updateUser'
	]);

Route::post('users/update_admin/{id}', [          
        'as' => 'users.update_admin',
        'uses' => 'UserController@updateAdmin',
     ]);

//------------------------------------------------

Route::get('/needs/create/', [
	'as' => 'needs.create_need',
	'uses' =>'NeedController@createNeed'
	]);

Route::post('/needs/save_need', 'NeedController@saveNeed');

Route::get('/materials/create/{type}', [
	'as' => 'materials.create_material',
	'uses' =>'MaterialController@createMaterial'
	]);

Route::post('/materials/create_text', 'MaterialController@saveText');
Route::post('/materials/create_video', 'MaterialController@saveVideo');
Route::post('/materials/create_image', 'MaterialController@saveImage');
Route::post('/materials/create_contact', 'MaterialController@saveContact');