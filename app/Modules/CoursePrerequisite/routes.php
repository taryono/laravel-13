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
Route::group(['prefix' => 'course-prerequisite', 'namespace' => 'App\Modules\CoursePrerequisite\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'course_prerequisite.index', 'uses' => 'CoursePrerequisiteController@index']);
    Route::any('/search', ['as' => 'course_prerequisite.search', 'uses' => 'CoursePrerequisiteController@search']);
    Route::get('/create', ['as' => 'course_prerequisite.create', 'uses' => 'CoursePrerequisiteController@create']);
    Route::get('/show/{id}', ['as' => 'course_prerequisite.show', 'uses' => 'CoursePrerequisiteController@show']);
    Route::get('/preview/{id}', ['as' => 'course_prerequisite.preview', 'uses' => 'CoursePrerequisiteController@preview']);
    Route::get('/print/{id}', ['as' => 'course_prerequisite.print', 'uses' => 'CoursePrerequisiteController@print']);

    Route::post('/store', ['as' => 'course_prerequisite.store', 'uses' => 'CoursePrerequisiteController@store']);
    Route::get('/edit/{id}', ['as' => 'course_prerequisite.edit', 'uses' => 'CoursePrerequisiteController@edit']);
    Route::put('/update/{id}', ['as' => 'course_prerequisite.update', 'uses' => 'CoursePrerequisiteController@update']);
    Route::delete('/delete/{id}', ['as' => 'course_prerequisite.destroy', 'uses' => 'CoursePrerequisiteController@destroy']);
    
});
