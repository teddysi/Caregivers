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
Auth::routes();

Route::get('/', 'UsersController@dashboard');

Route::group(['middleware' => 'auth', 'prefix' => 'users'], function () {
    Route::get('/', [
		'as' => 'users',
		'uses' =>'UsersController@index'
	]);
	Route::post('/', 'UsersController@index');
	
	Route::get('create/{role}', [
		'as' => 'users.create',
		'uses' =>'UsersController@create'
	]);
	Route::post('create', 'UsersController@store');

	Route::get('{user}', [
		'as' => 'users.show',
		'uses' =>'UsersController@show'
	]);

	Route::get('{user}/edit', [
		'as' => 'users.edit',
		'uses' =>'UsersController@edit'
	]);
	Route::patch('{user}', 'UsersController@update');

	Route::post('{user}/toggleBlock', [
		'as' => 'users.toggleBlock',
		'uses' =>'UsersController@toggleBlock'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'caregivers'], function () {
    Route::get('{caregiver}/patients', [
		'as' => 'caregivers.patients',
		'uses' =>'CaregiversController@patients'
	]);

	Route::post('{caregiver}/patients/{patient}/associate', [
		'as' => 'caregivers.associatePatient',
		'uses' =>'CaregiversController@associate'
	]);

	Route::post('{caregiver}/patients/{patient}/diassociate', [
		'as' => 'caregivers.diassociatePatient',
		'uses' =>'CaregiversController@diassociate'
	]);

	Route::get('{caregiver}/materials', [
		'as' => 'caregivers.materials',
		'uses' =>'CaregiversController@materials'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'patients'], function () {
    Route::get('/', [
		'as' => 'patients',
		'uses' =>'PatientsController@index'
	]);
	Route::post('/', 'PatientsController@index');
	
	Route::get('create', [
		'as' => 'patients.create',
		'uses' =>'PatientsController@create'
	]);
	Route::post('create', 'PatientsController@store');

	Route::get('{patient}', [
		'as' => 'patients.show',
		'uses' =>'PatientsController@show'
	]);

	Route::get('{patient}/edit', [
		'as' => 'patients.edit',
		'uses' =>'PatientsController@edit'
	]);
	Route::patch('{patient}', 'PatientsController@update');

	Route::get('{patient}/needs', [
		'as' => 'patients.needs',
		'uses' =>'PatientsController@needs'
	]);

	Route::post('{patient}/needs/{need}/associate', [
		'as' => 'patients.associateNeed',
		'uses' =>'PatientsController@associate'
	]);

	Route::post('{patient}/needs/{need}/diassociate', [
		'as' => 'patients.diassociateNeed',
		'uses' =>'PatientsController@diassociate'
	]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'needs'], function () {
    Route::get('/', [
		'as' => 'needs',
		'uses' =>'NeedsController@index'
	]);
	Route::post('/', 'NeedsController@index');
	
	Route::get('create', [
		'as' => 'needs.create',
		'uses' =>'NeedsController@create'
	]);
	Route::post('create', 'NeedsController@store');

	Route::get('{need}', [
		'as' => 'needs.show',
		'uses' =>'NeedsController@show'
	]);

	Route::get('{need}/edit', [
		'as' => 'needs.edit',
		'uses' =>'NeedsController@edit'
	]);
	Route::patch('{need}', 'NeedsController@update');

	Route::get('{need}/materials', [
		'as' => 'needs.materials',
		'uses' =>'NeedsController@materials'
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


// Caregivers API
Route::post('/caregiversAPI/login', 'CaregiversController@login');
Route::get('/caregiversAPI/{caregiver}/patients', 'CaregiversController@patientsAPI');
Route::get('/caregiversAPI/{caregiver}/materials', 'CaregiversController@caregiversMaterialsAPI');
Route::get('/caregiversAPI/{caregiver}/patients/{patient}/needs', 'CaregiversController@patientsNeeds');
Route::get('/caregiversAPI/{caregiver}/patients/{patient}/materials', 'CaregiversController@patientsMaterials');
Route::get('/caregiversAPI/{caregiver}/patients/{patient}/needs/{need}/materials', 'CaregiversController@patientsNeedsMaterials');
Route::get('/caregiversAPI/{caregiver}/proceedings', 'CaregiversController@proceedings');

Route::post('/proceedingsAPI/create', 'ProceedingsController@create');
Route::patch('/proceedingsAPI/{proceeding}', 'ProceedingsController@update');

