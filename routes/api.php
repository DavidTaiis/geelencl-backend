<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['prefix' => 'auth', 'namespace' => 'Api\Auth'], function () {
    Route::post('login', 'LoginController@login');
});

Route::group(['prefix' => 'user', 'namespace' => 'Api'/* ,'middleware' => 'auth:api' */ ], function () {
    Route::get('getUser', 'UserController@getUser');
    Route::post('updateProfileUser', 'UserController@updateProfileUser');
    Route::post('logout', 'UserController@logout');


});

Route::group(['prefix' => 'user', 'namespace' => 'Api'], function () {
    Route::post('register', 'UserController@register');
});




