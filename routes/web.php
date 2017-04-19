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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', 'AdminController@dashboard');

Route::get('/healthcarepros', 'AdminController@healthcarepros');

Route::get('/caregivers', [
	'as' => 'admin.admin_caregivers',
	'uses' => 'AdminController@caregivers'
	]);

Route::get('/patients', 'AdminController@patients');

Route::get('/needs', 'AdminController@needs');

Route::get('/materials', 'AdminController@materials');

Route::get('/healthcarepro{id}/caregivers', [
	'as' => 'admin.admin_healthcarepro_caregivers',
	'uses' =>'AdminController@healthcareproCaregivers'
	]);

Route::get('/caregiver{id}/patients', [
	'as' => 'admin.admin_caregiver_patients',
	'uses' =>'AdminController@caregiverPatients'
	]);

Route::get('/patient{id}/needs', [
	'as' => 'admin.admin_patient_needs',
	'uses' =>'AdminController@patientNeeds'
	]);

Route::get('/need{id}/materials', [
	'as' => 'admin.admin_need_materials',
	'uses' =>'AdminController@needMaterials'
	]);

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