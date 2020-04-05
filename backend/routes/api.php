<?php

use App\Http\Controllers\SocketsController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', 'UsersController@index');

Route::get('/sockets', 'SocketsController@index');
Route::get('/sockets/create', 'SocketsController@create');
Route::put('/sockets/{id}', 'SocketsController@update');
Route::delete('/sockets/{id}', 'SocketsController@delete');
