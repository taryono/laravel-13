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

Route::group(['prefix' => 'question-stimuli', 'namespace' => 'App\Modules\QuestionStimuli\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_stimuli.index', 'uses' => 'QuestionStimuliController@index']);
    Route::any('/search', ['as' => 'question_stimuli.search', 'uses' => 'QuestionStimuliController@search']);
    Route::get('/create', ['as' => 'question_stimuli.create', 'uses' => 'QuestionStimuliController@create']);
    Route::get('/show/{id}', ['as' => 'question_stimuli.show', 'uses' => 'QuestionStimuliController@show']);
    Route::get('/preview/{id}', ['as' => 'question_stimuli.preview', 'uses' => 'QuestionStimuliController@preview']);
    Route::get('/print/{id}', ['as' => 'question_stimuli.print', 'uses' => 'QuestionStimuliController@print']);

    Route::post('/store', ['as' => 'question_stimuli.store', 'uses' => 'QuestionStimuliController@store']);
    Route::get('/edit/{id}', ['as' => 'question_stimuli.edit', 'uses' => 'QuestionStimuliController@edit']);
    Route::put('/update/{id}', ['as' => 'question_stimuli.update', 'uses' => 'QuestionStimuliController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_stimuli.destroy', 'uses' => 'QuestionStimuliController@destroy']);
});
