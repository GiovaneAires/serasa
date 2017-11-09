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

Route::get('parceiro', 'Parceiro\ParceiroController@index')->middleware('autenticar');
Route::post('parceiro', 'Parceiro\ParceiroController@store')->middleware('autenticar');
Route::put('parceiro', 'Parceiro\ParceiroController@update')->middleware('autenticar');
Route::delete('parceiro', 'Parceiro\ParceiroController@update')->middleware('autenticar');