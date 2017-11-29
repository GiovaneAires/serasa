<?php

use Illuminate\Http\Request;

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

Route::post('parceiro', 'Parceiro\ParceiroController@store');
Route::get('parceiro', 'Parceiro\ParceiroController@index')->middleware('autenticar');
Route::put('parceiro', 'Parceiro\ParceiroController@update')->middleware('autenticar');
Route::delete('parceiro', 'Parceiro\ParceiroController@update')->middleware('autenticar');

Route::put('login', 'Login\LoginController@login');
Route::put('logout', 'Login\LoginController@logout')->middleware('autenticar');

Route::post('cliente', 'Cliente\ClienteController@store')->middleware('autenticar');
Route::get('cliente', 'Cliente\ClienteController@index')->middleware('autenticar');
Route::put('cliente/{id?}', 'Cliente\ClienteController@update')->middleware('autenticar');
Route::delete('cliente/{id?}', 'Cliente\ClienteController@destroy')->middleware('autenticar');

Route::post('titulo', 'Titulo\TituloController@store')->middleware('autenticar');
Route::get('titulo', 'Titulo\TituloController@index')->middleware('autenticar');
Route::put('titulo/{id?}', 'Titulo\TituloController@update')->middleware('autenticar');
Route::delete('titulo/{id?}', 'Titulo\TituloController@destroy')->middleware('autenticar');