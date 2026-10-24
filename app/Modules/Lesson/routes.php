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
Route::group(['prefix' => 'lesson', 'namespace' => 'App\Modules\Lesson\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'lesson.index', 'uses' => 'LessonController@index']);
    Route::any('/search', ['as' => 'lesson.search', 'uses' => 'LessonController@search']);
    Route::get('/create', ['as' => 'lesson.create', 'uses' => 'LessonController@create']);
    Route::get('/show/{id}', ['as' => 'lesson.show', 'uses' => 'LessonController@show']);
    Route::get('/preview/{id}', ['as' => 'lesson.preview', 'uses' => 'LessonController@preview']);
    Route::get('/print/{id}', ['as' => 'lesson.print', 'uses' => 'LessonController@print']);

    Route::post('/store', ['as' => 'lesson.store', 'uses' => 'LessonController@store']);
    Route::get('/edit/{id}', ['as' => 'lesson.edit', 'uses' => 'LessonController@edit']);
    Route::put('/update/{id}', ['as' => 'lesson.update', 'uses' => 'LessonController@update']);
    Route::delete('/delete/{id}', ['as' => 'lesson.destroy', 'uses' => 'LessonController@destroy']);
    
});
