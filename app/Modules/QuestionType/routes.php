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
Route::group(['prefix' => 'question-type', 'namespace' => 'App\Modules\QuestionType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_type.index', 'uses' => 'QuestionTypeController@index']);
    Route::any('/search', ['as' => 'question_type.search', 'uses' => 'QuestionTypeController@search']);
    Route::get('/create', ['as' => 'question_type.create', 'uses' => 'QuestionTypeController@create']);
    Route::get('/show/{id}', ['as' => 'question_type.show', 'uses' => 'QuestionTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'question_type.preview', 'uses' => 'QuestionTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'question_type.print', 'uses' => 'QuestionTypeController@print']);

    Route::post('/store', ['as' => 'question_type.store', 'uses' => 'QuestionTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'question_type.edit', 'uses' => 'QuestionTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'question_type.update', 'uses' => 'QuestionTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_type.destroy', 'uses' => 'QuestionTypeController@destroy']);
    
});
