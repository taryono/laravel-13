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
Route::group(['prefix' => 'tracer-study', 'namespace' => 'App\Modules\TracerStudy\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tracer_study.index', 'uses' => 'TracerStudyController@index']);
    Route::any('/search', ['as' => 'tracer_study.search', 'uses' => 'TracerStudyController@search']);
    Route::get('/create', ['as' => 'tracer_study.create', 'uses' => 'TracerStudyController@create']);
    Route::get('/show/{id}', ['as' => 'tracer_study.show', 'uses' => 'TracerStudyController@show']);
    Route::get('/preview/{id}', ['as' => 'tracer_study.preview', 'uses' => 'TracerStudyController@preview']);
    Route::get('/print/{id}', ['as' => 'tracer_study.print', 'uses' => 'TracerStudyController@print']);

    Route::post('/store', ['as' => 'tracer_study.store', 'uses' => 'TracerStudyController@store']);
    Route::get('/edit/{id}', ['as' => 'tracer_study.edit', 'uses' => 'TracerStudyController@edit']);
    Route::put('/update/{id}', ['as' => 'tracer_study.update', 'uses' => 'TracerStudyController@update']);
    Route::delete('/delete/{id}', ['as' => 'tracer_study.destroy', 'uses' => 'TracerStudyController@destroy']);
    
});
