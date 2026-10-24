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
Route::group(['prefix' => 'question-stimulus', 'namespace' => 'App\Modules\QuestionStimulus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_stimulus.index', 'uses' => 'QuestionStimulusController@index']);
    Route::any('/search', ['as' => 'question_stimulus.search', 'uses' => 'QuestionStimulusController@search']);
    Route::get('/create', ['as' => 'question_stimulus.create', 'uses' => 'QuestionStimulusController@create']);
    Route::get('/show/{id}', ['as' => 'question_stimulus.show', 'uses' => 'QuestionStimulusController@show']);
    Route::get('/preview/{id}', ['as' => 'question_stimulus.preview', 'uses' => 'QuestionStimulusController@preview']);
    Route::get('/print/{id}', ['as' => 'question_stimulus.print', 'uses' => 'QuestionStimulusController@print']);

    Route::post('/store', ['as' => 'question_stimulus.store', 'uses' => 'QuestionStimulusController@store']);
    Route::get('/edit/{id}', ['as' => 'question_stimulus.edit', 'uses' => 'QuestionStimulusController@edit']);
    Route::put('/update/{id}', ['as' => 'question_stimulus.update', 'uses' => 'QuestionStimulusController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_stimulus.destroy', 'uses' => 'QuestionStimulusController@destroy']);
    
});
