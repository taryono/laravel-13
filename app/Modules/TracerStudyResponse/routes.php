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
Route::group(['prefix' => 'tracer-study-response', 'namespace' => 'App\Modules\TracerStudyResponse\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tracer_study_response.index', 'uses' => 'TracerStudyResponseController@index']);
    Route::any('/search', ['as' => 'tracer_study_response.search', 'uses' => 'TracerStudyResponseController@search']);
    Route::get('/create', ['as' => 'tracer_study_response.create', 'uses' => 'TracerStudyResponseController@create']);
    Route::get('/show/{id}', ['as' => 'tracer_study_response.show', 'uses' => 'TracerStudyResponseController@show']);
    Route::get('/preview/{id}', ['as' => 'tracer_study_response.preview', 'uses' => 'TracerStudyResponseController@preview']);
    Route::get('/print/{id}', ['as' => 'tracer_study_response.print', 'uses' => 'TracerStudyResponseController@print']);

    Route::post('/store', ['as' => 'tracer_study_response.store', 'uses' => 'TracerStudyResponseController@store']);
    Route::get('/edit/{id}', ['as' => 'tracer_study_response.edit', 'uses' => 'TracerStudyResponseController@edit']);
    Route::put('/update/{id}', ['as' => 'tracer_study_response.update', 'uses' => 'TracerStudyResponseController@update']);
    Route::delete('/delete/{id}', ['as' => 'tracer_study_response.destroy', 'uses' => 'TracerStudyResponseController@destroy']);
    
});
