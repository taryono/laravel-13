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
Route::group(['prefix' => 'student-rubric-score', 'namespace' => 'App\Modules\StudentRubricScore\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_rubric_score.index', 'uses' => 'StudentRubricScoreController@index']);
    Route::any('/search', ['as' => 'student_rubric_score.search', 'uses' => 'StudentRubricScoreController@search']);
    Route::get('/create', ['as' => 'student_rubric_score.create', 'uses' => 'StudentRubricScoreController@create']);
    Route::get('/show/{id}', ['as' => 'student_rubric_score.show', 'uses' => 'StudentRubricScoreController@show']);
    Route::get('/preview/{id}', ['as' => 'student_rubric_score.preview', 'uses' => 'StudentRubricScoreController@preview']);
    Route::get('/print/{id}', ['as' => 'student_rubric_score.print', 'uses' => 'StudentRubricScoreController@print']);

    Route::post('/store', ['as' => 'student_rubric_score.store', 'uses' => 'StudentRubricScoreController@store']);
    Route::get('/edit/{id}', ['as' => 'student_rubric_score.edit', 'uses' => 'StudentRubricScoreController@edit']);
    Route::put('/update/{id}', ['as' => 'student_rubric_score.update', 'uses' => 'StudentRubricScoreController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_rubric_score.destroy', 'uses' => 'StudentRubricScoreController@destroy']);
    
});
