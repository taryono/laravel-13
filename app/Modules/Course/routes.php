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
Route::group(['prefix' => 'course', 'namespace' => 'App\Modules\Course\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'course.index', 'uses' => 'CourseController@index']);
    Route::any('/search', ['as' => 'course.search', 'uses' => 'CourseController@search']);
    Route::get('/create', ['as' => 'course.create', 'uses' => 'CourseController@create']);
    Route::get('/show/{id}', ['as' => 'course.show', 'uses' => 'CourseController@show']);
    Route::get('/preview/{id}', ['as' => 'course.preview', 'uses' => 'CourseController@preview']);
    Route::get('/print/{id}', ['as' => 'course.print', 'uses' => 'CourseController@print']);

    Route::post('/store', ['as' => 'course.store', 'uses' => 'CourseController@store']);
    Route::get('/edit/{id}', ['as' => 'course.edit', 'uses' => 'CourseController@edit']);
    Route::put('/update/{id}', ['as' => 'course.update', 'uses' => 'CourseController@update']);
    Route::delete('/delete/{id}', ['as' => 'course.destroy', 'uses' => 'CourseController@destroy']);
    
});
