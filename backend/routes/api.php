<?php

use App\Http\Controllers\SocketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Users
Route::get('/users', 'UsersController@index');
Route::get('/users/create', 'UsersController@create');
Route::get('/users/{id}', 'UsersController@show');
Route::put('/users/{id}', 'UsersController@update');
Route::delete('/users/{id}', 'UsersController@delete');


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