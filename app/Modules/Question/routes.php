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
Route::group(['prefix' => 'dit/question', 'namespace' => 'App\Modules\Question\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', ['as' => 'question.index', 'uses' => 'QuestionController@index']);
    Route::any('/search', ['as' => 'question.search', 'uses' => 'QuestionController@search']);
    Route::get('/create', ['as' => 'question.create', 'uses' => 'QuestionController@create']);
    Route::get('/show/{id}', ['as' => 'question.show', 'uses' => 'QuestionController@show']);
    Route::get('/preview/{id}', ['as' => 'question.preview', 'uses' => 'QuestionController@preview']);
    Route::get('/print/{id}', ['as' => 'question.print', 'uses' => 'QuestionController@print']);

    Route::post('/store', ['as' => 'question.store', 'uses' => 'QuestionController@store']);
    Route::get('/edit/{id}', ['as' => 'question.edit', 'uses' => 'QuestionController@edit']);
    Route::put('/update/{id}', ['as' => 'question.update', 'uses' => 'QuestionController@update']);
    Route::delete('/delete/{id}', ['as' => 'question.destroy', 'uses' => 'QuestionController@destroy']);
    
});
