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

});

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    Route::post('login', 'LoginController@login')->name('customLogin');
});

Route::get('politics', 'PoliticsController@index')->name('politics');
Auth::routes();
