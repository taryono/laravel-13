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
Route::group(['prefix' => 'question-option', 'namespace' => 'App\Modules\QuestionOption\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_option.index', 'uses' => 'QuestionOptionController@index']);
    Route::any('/search', ['as' => 'question_option.search', 'uses' => 'QuestionOptionController@search']);
    Route::get('/create', ['as' => 'question_option.create', 'uses' => 'QuestionOptionController@create']);
    Route::get('/show/{id}', ['as' => 'question_option.show', 'uses' => 'QuestionOptionController@show']);
    Route::get('/preview/{id}', ['as' => 'question_option.preview', 'uses' => 'QuestionOptionController@preview']);
    Route::get('/print/{id}', ['as' => 'question_option.print', 'uses' => 'QuestionOptionController@print']);

    Route::post('/store', ['as' => 'question_option.store', 'uses' => 'QuestionOptionController@store']);
    Route::get('/edit/{id}', ['as' => 'question_option.edit', 'uses' => 'QuestionOptionController@edit']);
    Route::put('/update/{id}', ['as' => 'question_option.update', 'uses' => 'QuestionOptionController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_option.destroy', 'uses' => 'QuestionOptionController@destroy']);
    
});
