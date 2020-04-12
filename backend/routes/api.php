<?php

use Illuminate\Support\Facades\Route;

// Authorization
Route::prefix('auth')->group(function () {
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
	Route::get('refresh', 'AuthController@refresh');
	Route::group(['middleware' => 'auth:api'], function () {
		Route::get('user', 'AuthController@user');
		Route::get('logout', 'AuthController@logout');
	});
});

// Users
Route::prefix('users')->group(function (){
	Route::get('list', 'UsersController@index');
	Route::group(['middleware' => 'auth:api'], function (){
		Route::put('{id}', 'UsersController@update');
		Route::delete('{id}', 'UsersController@delete');
	});
});

// Sockets
Route::get('/sockets', 'SocketsController@index');
Route::get('/sockets/create', 'SocketsController@create');
Route::get('/sockets/{id}', 'SocketsController@show');
Route::put('/sockets/{id}', 'SocketsController@update');
Route::delete('/sockets/{id}', 'SocketsController@delete');

// Data
Route::get('/data', 'DataController@index');
Route::get('/data/create', 'DataController@create');
Route::get('/data/{id}', 'DataController@show');
Route::put('/data/{id}', 'DataController@update');
Route::delete('/data/{id}', 'DataController@delete');