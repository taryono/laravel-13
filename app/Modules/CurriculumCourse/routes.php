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
Route::group(['prefix' => 'curriculum-course', 'namespace' => 'App\Modules\CurriculumCourse\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_course.index', 'uses' => 'CurriculumCourseController@index']);
    Route::any('/search', ['as' => 'curriculum_course.search', 'uses' => 'CurriculumCourseController@search']);
    Route::get('/create', ['as' => 'curriculum_course.create', 'uses' => 'CurriculumCourseController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_course.show', 'uses' => 'CurriculumCourseController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_course.preview', 'uses' => 'CurriculumCourseController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_course.print', 'uses' => 'CurriculumCourseController@print']);

    Route::post('/store', ['as' => 'curriculum_course.store', 'uses' => 'CurriculumCourseController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_course.edit', 'uses' => 'CurriculumCourseController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_course.update', 'uses' => 'CurriculumCourseController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_course.destroy', 'uses' => 'CurriculumCourseController@destroy']);
    
});
