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
Route::group(['prefix' => 'student-assessment', 'namespace' => 'App\Modules\StudentAssessment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_assessment.index', 'uses' => 'StudentAssessmentController@index']);
    Route::any('/search', ['as' => 'student_assessment.search', 'uses' => 'StudentAssessmentController@search']);
    Route::get('/create', ['as' => 'student_assessment.create', 'uses' => 'StudentAssessmentController@create']);
    Route::get('/show/{id}', ['as' => 'student_assessment.show', 'uses' => 'StudentAssessmentController@show']);
    Route::get('/preview/{id}', ['as' => 'student_assessment.preview', 'uses' => 'StudentAssessmentController@preview']);
    Route::get('/print/{id}', ['as' => 'student_assessment.print', 'uses' => 'StudentAssessmentController@print']);

    Route::post('/store', ['as' => 'student_assessment.store', 'uses' => 'StudentAssessmentController@store']);
    Route::get('/edit/{id}', ['as' => 'student_assessment.edit', 'uses' => 'StudentAssessmentController@edit']);
    Route::put('/update/{id}', ['as' => 'student_assessment.update', 'uses' => 'StudentAssessmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_assessment.destroy', 'uses' => 'StudentAssessmentController@destroy']);
    
});
