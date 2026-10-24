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
Route::group(['prefix' => 'quiz-attempt', 'namespace' => 'App\Modules\QuizAttempt\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'quiz_attempt.index', 'uses' => 'QuizAttemptController@index']);
    Route::any('/search', ['as' => 'quiz_attempt.search', 'uses' => 'QuizAttemptController@search']);
    Route::get('/create', ['as' => 'quiz_attempt.create', 'uses' => 'QuizAttemptController@create']);
    Route::get('/show/{id}', ['as' => 'quiz_attempt.show', 'uses' => 'QuizAttemptController@show']);
    Route::get('/preview/{id}', ['as' => 'quiz_attempt.preview', 'uses' => 'QuizAttemptController@preview']);
    Route::get('/print/{id}', ['as' => 'quiz_attempt.print', 'uses' => 'QuizAttemptController@print']);

    Route::post('/store', ['as' => 'quiz_attempt.store', 'uses' => 'QuizAttemptController@store']);
    Route::get('/edit/{id}', ['as' => 'quiz_attempt.edit', 'uses' => 'QuizAttemptController@edit']);
    Route::put('/update/{id}', ['as' => 'quiz_attempt.update', 'uses' => 'QuizAttemptController@update']);
    Route::delete('/delete/{id}', ['as' => 'quiz_attempt.destroy', 'uses' => 'QuizAttemptController@destroy']);
    
});
