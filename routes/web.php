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

	Route::group(['middleware' => 'healthcarepro'], function () {
		Route::get('{user}/caregivers', [
			'as' => 'users.caregivers',
			'uses' =>'UsersController@caregivers'
		]);

		Route::post('{user}/caregivers/{caregiver}/associate', [
			'as' => 'users.associateCaregiver',
			'uses' =>'UsersController@associate'
		]);

		Route::post('{user}/caregivers/{caregiver}/diassociate', [
			'as' => 'users.diassociateCaregiver',
			'uses' =>'UsersController@diassociate'
		]);
	});
});

Route::group(['middleware' => ['auth', 'healthcarepro'], 'prefix' => 'caregivers'], function () {
    Route::get('{caregiver}/patients', [
		'as' => 'caregivers.patients',
		'uses' =>'CaregiversController@patients'
	]);

	Route::post('{caregiver}/patients/{patient}/associate', [
		'as' => 'caregivers.associatePatient',
		'uses' =>'CaregiversController@associatePatient'
	]);

	Route::post('{caregiver}/patients/{patient}/diassociate', [
		'as' => 'caregivers.diassociatePatient',
		'uses' =>'CaregiversController@diassociatePatient'
	]);

	Route::get('{caregiver}/materials', [
		'as' => 'caregivers.materials',
		'uses' =>'CaregiversController@materials'
	]);

	Route::post('{caregiver}/materials/associate', [
		'as' => 'caregivers.associateMaterial',
		'uses' =>'CaregiversController@associateMaterial'
	]);

	Route::post('{caregiver}/materials/{material}/diassociate', [
		'as' => 'caregivers.diassociateMaterial',
		'uses' =>'CaregiversController@diassociateMaterial'
	]);

	Route::get('{caregiver}/rate', [
		'as' => 'caregivers.rate',
		'uses' =>'CaregiversController@rate'
	]);

	Route::get('{id}/evaluation/create', [
		'as' => 'caregivers.evaluations.create',
		'uses' =>'EvaluationsController@create'
	]);

	Route::post('{id}/evaluation', [
		'as' => 'evaluations.createForCaregiver',
		'uses' =>'EvaluationsController@store'
	]);
});

Route::group(['middleware' => ['auth', 'healthcarepro'], 'prefix' => 'patients'], function () {
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

	Route::get('{id}/evaluation/create', [
		'as' => 'patients.evaluations.create',
		'uses' =>'EvaluationsController@create'
	]);

	Route::post('{id}/evaluation', [
		'as' => 'evaluations.createForPatient',
		'uses' =>'EvaluationsController@store'
	]);
});

Route::group(['middleware' => ['auth', 'healthcarepro'], 'prefix' => 'needs'], function () {
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

	Route::post('{need}/materials/{material}/diassociate', [
		'as' => 'needs.diassociateMaterial',
		'uses' =>'NeedsController@diassociateMaterial'
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

	Route::get('{material}/showContent', [
		'as' => 'materials.showContent',
		'uses' =>'MaterialsController@showMaterial'
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

	Route::get('{material}/materials', [
		'as' => 'materials.materials',
		'uses' =>'MaterialsController@materials'
	]);
	Route::post('add', 'MaterialsController@addMaterials');

	Route::post('{composite}/add/{material}/add', [
		'as' => 'materials.addMaterial',
		'uses' =>'MaterialsController@addMaterial'
	]);

	Route::post('{composite}/add/{material}/remove', [
		'as' => 'materials.removeMaterial',
		'uses' =>'MaterialsController@removeMaterial'
	]);

	Route::post('{composite}/add/{material}/up', [
		'as' => 'materials.upMaterial',
		'uses' =>'MaterialsController@upMaterial'
	]);

	Route::post('{composite}/add/{material}/down', [
		'as' => 'materials.downMaterial',
		'uses' =>'MaterialsController@downMaterial'
	]);
});

Route::group(['middleware' => ['auth', 'healthcarepro'], 'prefix' => 'evaluations'], function () {
    Route::get('{evaluation}', [
		'as' => 'evaluations.show',
		'uses' =>'EvaluationsController@show'
	]);

	Route::get('{evaluation}/showContent', [
		'as' => 'evaluations.showContent',
		'uses' =>'EvaluationsController@showEvaluation'
	]);

	Route::get('{evaluation}/edit', [
		'as' => 'evaluations.edit',
		'uses' =>'EvaluationsController@edit'
	]);
	Route::patch('{evaluation}', 'EvaluationsController@update');
});

Route::group(['middleware' => ['auth', 'healthcarepro'], 'prefix' => 'quizs'], function () {
	Route::get('/', [
		'as' => 'quizs',
		'uses' =>'QuizsController@index'
	]);
	Route::post('/', 'QuizsController@index');

	Route::get('create', [
		'as' => 'quizs.create',
		'uses' => 'QuizsController@create'
	]);
	Route::post('create', 'QuizsController@store');

	Route::get('{quiz}', [
		'as' => 'quizs.show',
		'uses' =>'QuizsController@show'
	]);

	Route::get('{quiz}/edit', [
		'as' => 'quizs.edit',
		'uses' =>'QuizsController@edit'
	]);
	Route::patch('{quiz}', 'QuizsController@update');

	Route::delete('{quiz}/delete', [
		'as' => 'quizs.delete',
		'uses' =>'QuizsController@delete'
	]);

	Route::get('{quiz}/questions', [
		'as' => 'quizs.questions',
		'uses' =>'QuizsController@questions'
	]);

	Route::post('{quiz}/add/{question}/add', [
		'as' => 'quizs.addQuestion',
		'uses' =>'QuizsController@addQuestion'
	]);

	Route::post('{quiz}/add/{question}/remove', [
		'as' => 'quizs.removeQuestion',
		'uses' =>'QuizsController@removeQuestion'
	]);

	Route::post('{quiz}/add/{question}/up', [
		'as' => 'quizs.upQuestion',
		'uses' =>'QuizsController@upQuestion'
	]);

	Route::post('{quiz}/add/{question}/down', [
		'as' => 'quizs.downQuestion',
		'uses' =>'QuizsController@downQuestion'
	]);
});


Route::group(['middleware' => ['auth', 'healthcarepro'], 'prefix' => 'questions'], function () {
	Route::get('/', [
		'as' => 'questions',
		'uses' =>'QuestionsController@index'
	]);
	Route::post('/', 'QuestionsController@index');

	Route::get('create', [
		'as' => 'questions.create',
		'uses' => 'QuestionsController@create'
	]);
	Route::post('create', 'QuestionsController@store');

	Route::get('{question}', [
		'as' => 'questions.show',
		'uses' =>'QuestionsController@show'
	]);

	Route::get('{question}/edit', [
		'as' => 'questions.edit',
		'uses' =>'QuestionsController@edit'
	]);
	Route::patch('{question}', 'QuestionsController@update');

	Route::delete('{question}/delete', [
		'as' => 'questions.delete',
		'uses' =>'QuestionsController@delete'
	]);
});

// Caregivers API: missing authorization on showContent
Route::post('/caregiversAPI/login', 'CaregiversController@login');
Route::get('/caregiversAPI/{caregiver}/patients', 'CaregiversController@patientsAPI');
Route::get('/materialsAPI/{material}/showContent', 'MaterialsController@showMaterialAPI');
Route::get('/caregiversAPI/{caregiver}/proceedings', 'CaregiversController@proceedings');

Route::post('/proceedingsAPI/create', 'ProceedingsController@create');
Route::patch('/proceedingsAPI/{proceeding}', 'ProceedingsController@update');

