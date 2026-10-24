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
Route::group(['prefix' => 'lesson-plan', 'namespace' => 'App\Modules\LessonPlan\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'lesson_plan.index', 'uses' => 'LessonPlanController@index']);
    Route::any('/search', ['as' => 'lesson_plan.search', 'uses' => 'LessonPlanController@search']);
    Route::get('/create', ['as' => 'lesson_plan.create', 'uses' => 'LessonPlanController@create']);
    Route::get('/show/{id}', ['as' => 'lesson_plan.show', 'uses' => 'LessonPlanController@show']);
    Route::get('/preview/{id}', ['as' => 'lesson_plan.preview', 'uses' => 'LessonPlanController@preview']);
    Route::get('/print/{id}', ['as' => 'lesson_plan.print', 'uses' => 'LessonPlanController@print']);

    Route::post('/store', ['as' => 'lesson_plan.store', 'uses' => 'LessonPlanController@store']);
    Route::get('/edit/{id}', ['as' => 'lesson_plan.edit', 'uses' => 'LessonPlanController@edit']);
    Route::put('/update/{id}', ['as' => 'lesson_plan.update', 'uses' => 'LessonPlanController@update']);
    Route::delete('/delete/{id}', ['as' => 'lesson_plan.destroy', 'uses' => 'LessonPlanController@destroy']);
    
});
