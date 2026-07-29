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
Route::group(['prefix' => 'dit/question', 'namespace' => 'App\Modules\Question\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question.index', 'uses' => 'QuestionController@index']);
    Route::any('/search', ['as' => 'question.search', 'uses' => 'QuestionController@search']);
    Route::get('/create', ['as' => 'question.create', 'uses' => 'QuestionController@create']);
    Route::get('/show/{question}', ['as' => 'question.show', 'uses' => 'QuestionController@show']);
    Route::get('/preview/{question}', ['as' => 'question.preview', 'uses' => 'QuestionController@preview']);
    Route::get('/print/{question}', ['as' => 'question.print', 'uses' => 'QuestionController@print']);

    Route::post('/store', ['as' => 'question.store', 'uses' => 'QuestionController@store']);
    Route::get('/edit/{question}', ['as' => 'question.edit', 'uses' => 'QuestionController@edit']);
    Route::put('/update/{question}', ['as' => 'question.update', 'uses' => 'QuestionController@update']);
    Route::delete('/delete/{question}', ['as' => 'question.destroy', 'uses' => 'QuestionController@destroy']);
    
});
