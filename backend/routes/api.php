<?php

use Illuminate\Support\Facades\Route;

// DEBUG
Route::get('users/list', 'UsersController@index');
Route::get('sockets/list', 'SocketsController@index');
Route::get('data/list', 'DataController@index');


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
Route::prefix('user')->group(function (){
	Route::group(['middleware' => 'auth:api'], function (){
		Route::put('update', 'UsersController@update');
		Route::delete('delete', 'UsersController@delete');
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