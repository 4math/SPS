<?php

use Illuminate\Support\Facades\Route;

// DEBUG
Route::get('users/listD', 'UsersController@indexD');
Route::get('sockets/listD', 'SocketsController@indexD');
Route::get('measurements/listD', 'MeasurementsController@indexD');


// Authorization
Route::prefix('auth')->group(function () {
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
	Route::get('refresh', 'AuthController@refresh');
	Route::group(['middleware' => ['auth:api', 'jwt.auth', 'jwt.refresh', 'cors']], function () {
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
	Route::get('state', 'SocketsController@getState');
	Route::group(['middleware' => 'auth:api'], function (){
		Route::put('connect', 'SocketsController@connect');
		Route::get('list', 'SocketsController@list');
		Route::get('show/{id}', 'SocketsController@show');
		Route::put('{id}', 'SocketsController@put');
		Route::delete('{id}', 'SocketsController@delete');
	});
});

// Data
Route::prefix('measurements')->group(function (){
	Route::group(['middleware' => 'auth:api'], function (){
		Route::get('list/{socket_id}', 'MeasurementsController@list');
		Route::delete('{id}', 'MeasurementsController@delete');
	});
});