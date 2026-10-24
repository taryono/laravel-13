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
Route::group(['prefix' => 'assignments-quiz', 'namespace' => 'App\Modules\AssignmentsQuiz\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'assignments_quiz.index', 'uses' => 'AssignmentsQuizController@index']);
    Route::any('/search', ['as' => 'assignments_quiz.search', 'uses' => 'AssignmentsQuizController@search']);
    Route::get('/create', ['as' => 'assignments_quiz.create', 'uses' => 'AssignmentsQuizController@create']);
    Route::get('/show/{id}', ['as' => 'assignments_quiz.show', 'uses' => 'AssignmentsQuizController@show']);
    Route::get('/preview/{id}', ['as' => 'assignments_quiz.preview', 'uses' => 'AssignmentsQuizController@preview']);
    Route::get('/print/{id}', ['as' => 'assignments_quiz.print', 'uses' => 'AssignmentsQuizController@print']);

    Route::post('/store', ['as' => 'assignments_quiz.store', 'uses' => 'AssignmentsQuizController@store']);
    Route::get('/edit/{id}', ['as' => 'assignments_quiz.edit', 'uses' => 'AssignmentsQuizController@edit']);
    Route::put('/update/{id}', ['as' => 'assignments_quiz.update', 'uses' => 'AssignmentsQuizController@update']);
    Route::delete('/delete/{id}', ['as' => 'assignments_quiz.destroy', 'uses' => 'AssignmentsQuizController@destroy']);
    
});
