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
Route::group(['prefix' => 'student-learning-plan', 'namespace' => 'App\Modules\StudentLearningPlan\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_learning_plan.index', 'uses' => 'StudentLearningPlanController@index']);
    Route::any('/search', ['as' => 'student_learning_plan.search', 'uses' => 'StudentLearningPlanController@search']);
    Route::get('/create', ['as' => 'student_learning_plan.create', 'uses' => 'StudentLearningPlanController@create']);
    Route::get('/show/{id}', ['as' => 'student_learning_plan.show', 'uses' => 'StudentLearningPlanController@show']);
    Route::get('/preview/{id}', ['as' => 'student_learning_plan.preview', 'uses' => 'StudentLearningPlanController@preview']);
    Route::get('/print/{id}', ['as' => 'student_learning_plan.print', 'uses' => 'StudentLearningPlanController@print']);

    Route::post('/store', ['as' => 'student_learning_plan.store', 'uses' => 'StudentLearningPlanController@store']);
    Route::get('/edit/{id}', ['as' => 'student_learning_plan.edit', 'uses' => 'StudentLearningPlanController@edit']);
    Route::put('/update/{id}', ['as' => 'student_learning_plan.update', 'uses' => 'StudentLearningPlanController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_learning_plan.destroy', 'uses' => 'StudentLearningPlanController@destroy']);
    
});
