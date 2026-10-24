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
Route::group(['prefix' => 'tracer-study-question', 'namespace' => 'App\Modules\TracerStudyQuestion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tracer_study_question.index', 'uses' => 'TracerStudyQuestionController@index']);
    Route::any('/search', ['as' => 'tracer_study_question.search', 'uses' => 'TracerStudyQuestionController@search']);
    Route::get('/create', ['as' => 'tracer_study_question.create', 'uses' => 'TracerStudyQuestionController@create']);
    Route::get('/show/{id}', ['as' => 'tracer_study_question.show', 'uses' => 'TracerStudyQuestionController@show']);
    Route::get('/preview/{id}', ['as' => 'tracer_study_question.preview', 'uses' => 'TracerStudyQuestionController@preview']);
    Route::get('/print/{id}', ['as' => 'tracer_study_question.print', 'uses' => 'TracerStudyQuestionController@print']);

    Route::post('/store', ['as' => 'tracer_study_question.store', 'uses' => 'TracerStudyQuestionController@store']);
    Route::get('/edit/{id}', ['as' => 'tracer_study_question.edit', 'uses' => 'TracerStudyQuestionController@edit']);
    Route::put('/update/{id}', ['as' => 'tracer_study_question.update', 'uses' => 'TracerStudyQuestionController@update']);
    Route::delete('/delete/{id}', ['as' => 'tracer_study_question.destroy', 'uses' => 'TracerStudyQuestionController@destroy']);
    
});
