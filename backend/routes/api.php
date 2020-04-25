<?php

use Illuminate\Support\Facades\Route;

// DEBUG
Route::get('users/listD', 'UsersController@indexD');
Route::get('sockets/listD', 'SocketsController@indexD');
Route::get('data/listD', 'DataController@indexD');


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
		Route::delete('', 'UsersController@delete');
	});
});

// Sockets
Route::prefix('sockets')->group(function (){
	Route::group(['middleware' => 'auth:api'], function (){
		Route::post('add', 'SocketsController@add');
		Route::get('list', 'SocketsController@list');
		Route::get('show/{id}', 'SocketsController@show');
		Route::delete('{id}', 'SocketsController@delete');
	});
});

// Data
Route::prefix('data')->group(function (){
	Route::group(['middleware' => 'auth:api'], function (){
		Route::get('list/{socket_id}', 'DataController@list');
		Route::delete('{id}', 'DataController@delete');
	});
});