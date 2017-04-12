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

Route::get('/needs', 'UserController@needs');

Route::get('/materials', 'UserController@materials');

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


