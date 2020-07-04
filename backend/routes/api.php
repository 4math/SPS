<?php

use Illuminate\Support\Facades\Route;

// Authorization
Route::prefix('auth')->group(function () {
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
	Route::group(['middleware' => ['auth:api', 'jwt.auth', 'jwt.refresh', 'cors']], function () {
		Route::get('refresh', 'AuthController@refresh');
		Route::get('user', 'AuthController@user');
		Route::get('logout', 'AuthController@logout');
	});
});

// Users
Route::prefix('user')->group(function (){
	Route::group(['middleware' => 'auth:api'], function (){
		Route::put('update', 'UsersController@update');
		Route::delete('', 'UsersController@delete');
	});
});

// Sockets
Route::prefix('sockets')->group(function (){
	Route::post('add', 'SocketsController@add');
	Route::group(['middleware' => 'auth:api'], function (){
		Route::put('connect', 'SocketsController@connect');
		Route::get('list', 'SocketsController@list');
		Route::get('show/{id}', 'SocketsController@show');
		Route::put('update-info/{id}', 'SocketsController@updateInfo');
		Route::put('{id}', 'SocketsController@put');
		Route::delete('{id}', 'SocketsController@delete');
	});
});

// Measurements
Route::prefix('measurements')->group(function (){
	Route::post('add', 'MeasurementsController@add');
	Route::group(['middleware' => 'auth:api'], function (){
		Route::get('list/{socket_id}', 'MeasurementsController@list');
		Route::delete('{id}', 'MeasurementsController@delete');
		Route::post('get-period/{id}', 'MeasurementsController@getDataInPeriod');
	});
});