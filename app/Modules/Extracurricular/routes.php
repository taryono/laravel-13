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

Route::group(['prefix' => 'extracurricular', 'namespace' => 'App\Modules\Extracurricular\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'extracurricular.index', 'uses' => 'ExtracurricularController@index']);
    Route::any('/search', ['as' => 'extracurricular.search', 'uses' => 'ExtracurricularController@search']);
    Route::get('/create', ['as' => 'extracurricular.create', 'uses' => 'ExtracurricularController@create']);
    Route::get('/show/{id}', ['as' => 'extracurricular.show', 'uses' => 'ExtracurricularController@show']);
    Route::get('/preview/{id}', ['as' => 'extracurricular.preview', 'uses' => 'ExtracurricularController@preview']);
    Route::get('/print/{id}', ['as' => 'extracurricular.print', 'uses' => 'ExtracurricularController@print']);

    Route::post('/store', ['as' => 'extracurricular.store', 'uses' => 'ExtracurricularController@store']);
    Route::get('/edit/{id}', ['as' => 'extracurricular.edit', 'uses' => 'ExtracurricularController@edit']);
    Route::put('/update/{id}', ['as' => 'extracurricular.update', 'uses' => 'ExtracurricularController@update']);
    Route::delete('/delete/{id}', ['as' => 'extracurricular.destroy', 'uses' => 'ExtracurricularController@destroy']);
});
