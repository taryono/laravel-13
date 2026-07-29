<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
Route::group(['prefix' => 'asesor', 'namespace' => 'App\Modules\Asesor\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'asesor.index', 'uses' => 'AsesorController@index']);
    Route::any('/search', ['as' => 'asesor.search', 'uses' => 'AsesorController@search']);
    Route::get('/create', ['as' => 'asesor.create', 'uses' => 'AsesorController@create']);
    Route::get('/show/{id}', ['as' => 'asesor.show', 'uses' => 'AsesorController@show']);
    Route::get('/preview/{id}', ['as' => 'asesor.preview', 'uses' => 'AsesorController@preview']);
    Route::get('/print/{id}', ['as' => 'asesor.print', 'uses' => 'AsesorController@print']);

    Route::post('/store', ['as' => 'asesor.store', 'uses' => 'AsesorController@store']);
    Route::get('/edit/{id}', ['as' => 'asesor.edit', 'uses' => 'AsesorController@edit']);
    Route::put('/update/{id}', ['as' => 'asesor.update', 'uses' => 'AsesorController@update']);
    Route::delete('/delete/{id}', ['as' => 'asesor.destroy', 'uses' => 'AsesorController@destroy']);
});
