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

Route::get('/api/img/{path}', 'Multimedia\ImageController@show')->where('path', '.*');
Route::group(['middleware' => ['auth', 'rbac']], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/index', 'HomeController@index')->name('home');
    Route::get('/formatActivities', 'HomeController@formatActivities')->name('formatActivities');
    Route::group(['prefix' => 'rbac', 'namespace' => 'Rbac'], function () {
        Route::group(['prefix' => 'role'], function () {
            Route::get('role', 'RoleController@index')->name('viewIndexRole');
            Route::get('index', 'RoleController@index')->name('viewIndexRole');
            Route::get('form', 'RoleController@getFormRole')->name('getFormRole');
            Route::get('form/{id?}', 'RoleController@getFormRole')->name('getFormRole');
            Route::get('list', 'RoleController@getList')->name('listDataRole');
            Route::get('list/select', 'RoleController@getListSelect2')->name('listDataSelectRole');
            Route::post('unique-name', 'RoleController@postIsNameUnique')->name('uniqueNameRole');
            Route::post('save', 'RoleController@postSave')->name('saveRole');
        });
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', 'UserController@index')->name('viewIndexUser');
            Route::get('index', 'UserController@index')->name('viewIndexUser');
            Route::get('form', 'UserController@getForm')->name('getFormUser');
            Route::get('form/{id?}', 'UserController@getForm')->name('getFormUser');
            Route::get('list', 'UserController@getList')->name('listDataUser');
            Route::post('unique-email', 'UserController@postIsEmailUnique')->name('uniqueEmailUser');
            Route::post('unique-name', 'UserController@postIsNameUnique')->name('uniqueNameUser');
            Route::post('save', 'UserController@postSave')->name('saveUser');
            Route::post('/save-uploads', 'UserController@postSaveUpload')->name('UploadUsers');
        });
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('/', 'CompanyController@index')->name('indexViewCompany');
        Route::get('/list', 'CompanyController@getList')->name('getListDataCompany');
        Route::get('/form/{id?}', 'CompanyController@getForm')->name('getFormCompany');
        Route::post('save', 'CompanyController@postSave')->name('saveCompany');
        Route::post('unique-name', 'CompanyController@postIsNameUnique')->name('uniqueNameCompany');
        /* Route::get('/view', 'CompanyController@view')->name('viewProfileCompany');
        Route::post('updateCompany', 'CompanyController@updateCompanyUser')->name('updateCompany'); */
    });

    Route::group(['prefix' => 'provider'], function () {
        Route::get('/', 'ProviderController@index')->name('indexViewProvider');
        Route::get('/list', 'ProviderController@getList')->name('getListDataProvider');
        Route::get('/form/{id?}', 'ProviderController@getForm')->name('getFormProvider');
        Route::post('save', 'ProviderController@postSave')->name('saveProvider');
        Route::post('unique-name', 'ProviderController@postIsNameUnique')->name('uniqueNameProvider');
        /* Route::get('/view', 'CompanyController@view')->name('viewProfileCompany');
        Route::post('updateCompany', 'CompanyController@updateCompanyUser')->name('updateCompany'); */
    });

    Route::group(['prefix' => 'multimedia', 'namespace' => 'Multimedia'], function () {
        Route::group(['prefix' => 'image-parameter'], function () {
            Route::get('/', 'ImageParameterController@index')->name('viewIndexMultimedia');
            Route::get('index', 'ImageParameterController@index')->name('viewIndexMultimedia');
            Route::get('form', 'ImageParameterController@getForm')->name('getFormMultimedia');
            Route::get('form/{id?}', 'ImageParameterController@getForm')->name('getFormMultimedia');
            Route::get('list', 'ImageParameterController@getList')->name('listDataMultimedia');
            Route::post('unique-name', 'ImageParameterController@postIsNameUnique')->name('uniqueNameMultimedia');
            Route::post('unique-entity', 'ImageParameterController@postIsEntityUnique')->name('viewEntityMultimedia');
            Route::post('save', 'ImageParameterController@postSave')->name('saveMultimedia');
        });
    });
        
    Route::group(['prefix' => 'typeProvider'], function () {
        Route::get('/', 'TypeProviderController@index')->name('viewIndexTypeProvider');
        Route::get('/form/{id?}', 'TypeProviderController@getForm')->name('getFormTypeProvider');
        Route::get('/list', 'TypeProviderController@getList')->name('getListDataTypeProvider');
        Route::post('/save', 'TypeProviderController@postSave')->name('saveTypeProvider');
        Route::post('/save/uploads', 'TypeProviderController@postSaveUpload')->name('uploadTypeProvider');
    });  

    
    Route::group(['prefix' => 'answers'], function () {
        Route::get('/', 'AnswersController@index')->name('viewIndexAnswers');
        Route::get('/form/{id?}', 'AnswersController@getForm')->name('getFormAnswers');
        Route::get('/list', 'AnswersController@getList')->name('getListDataAnswers');
        Route::post('/save', 'AnswersController@postSave')->name('saveAnswers');
        Route::post('/save/uploads', 'AnswersController@postSaveUpload')->name('uploadAnswers');
    });

    Route::group(['prefix' => 'section'], function () {
        Route::get('/', 'SectionController@index')->name('viewIndexSection');
        Route::get('/form/{id?}', 'SectionController@getForm')->name('getFormSection');
        Route::get('/list', 'SectionController@getList')->name('getListDataSection');
        Route::post('/save', 'SectionController@postSave')->name('saveSection');
        Route::post('/save/uploads', 'SectionController@postSaveUpload')->name('uploadSection');
    });

    Route::group(['prefix' => 'question'], function () {
        Route::get('/{id?}', 'QuestionController@index')->name('viewIndexQuestion');
        Route::get('/form/{sectionId?}/{id?}', 'QuestionController@getForm')->name('getFormQuestion');
        Route::get('/list/{id?}', 'QuestionController@getList')->name('getListDataQuestion');
        Route::post('/save', 'QuestionController@postSave')->name('saveQuestion');
        Route::post('/save/uploads', 'QuestionController@postSaveUpload')->name('uploadQuestion');
        Route::delete('deleted/{id?}', 'QuestionController@deletedQuestion')->name('deletedQuestion');

    });
    
    Route::group(['prefix' => 'documents'], function () {
        Route::get('/', 'ManualController@index')->name('viewIndexManual');
        Route::get('/form/{id?}', 'ManualController@getForm')->name('getFormManual');
        Route::get('/list', 'ManualController@getList')->name('getListDataManual');
        Route::post('/save', 'ManualController@postSave')->name('saveManual');
        Route::post('/save/uploads', 'ManualController@postSaveUpload')->name('uploadManual');
    });

//Rol proveedor
    Route::group(['prefix' => 'providersCompany'], function () {
        Route::get('/', 'ProviderCompanyController@index')->name('viewIndexProviderCompany');
        Route::post('/save', 'ProviderCompanyController@postSave')->name('saveProviderCompany');
    });

//Rol Empresa
    Route::group(['prefix' => 'companyProviders'], function () {
        Route::get('/', 'CompanyProvidersController@index')->name('viewIndexCompanyProviders');
        Route::get('/list', 'CompanyProvidersController@getList')->name('getListDataCompanyProviders');
        Route::get('/{id?}', 'CompanyProvidersController@indexInformation')->name('viewIndexInformationProvider');
        Route::post('/saveQualification', 'CompanyProvidersController@qualification')->name('qualificationProvider');

    });

    Route::group(['prefix' => 'profileCompany'], function () {
        Route::get('/', 'CompanyController@indexProfile')->name('viewIndexCompanyProfile');
        Route::post('/save', 'CompanyController@postSaveProfile')->name('saveCompanyProfile');
    });

});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    Route::post('login', 'LoginController@login')->name('customLogin');
});

Route::get('politics', 'PoliticsController@index')->name('politics');
Auth::routes();
