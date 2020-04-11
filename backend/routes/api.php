<?php

use App\Http\Controllers\SocketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

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
Route::get('/users', 'UsersController@index');
Route::middleware('auth:api')->get('/users/create', 'UsersController@create');
Route::middleware('auth:api')->get('/users/{id}', 'UsersController@show');
Route::middleware('auth:api')->put('/users/{id}', 'UsersController@update');
Route::middleware('auth:api')->delete('/users/{id}', 'UsersController@delete');


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