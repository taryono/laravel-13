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
Route::group(['prefix' => 'lesson-progress', 'namespace' => 'App\Modules\LessonProgress\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'lesson_progress.index', 'uses' => 'LessonProgressController@index']);
    Route::any('/search', ['as' => 'lesson_progress.search', 'uses' => 'LessonProgressController@search']);
    Route::get('/create', ['as' => 'lesson_progress.create', 'uses' => 'LessonProgressController@create']);
    Route::get('/show/{id}', ['as' => 'lesson_progress.show', 'uses' => 'LessonProgressController@show']);
    Route::get('/preview/{id}', ['as' => 'lesson_progress.preview', 'uses' => 'LessonProgressController@preview']);
    Route::get('/print/{id}', ['as' => 'lesson_progress.print', 'uses' => 'LessonProgressController@print']);

    Route::post('/store', ['as' => 'lesson_progress.store', 'uses' => 'LessonProgressController@store']);
    Route::get('/edit/{id}', ['as' => 'lesson_progress.edit', 'uses' => 'LessonProgressController@edit']);
    Route::put('/update/{id}', ['as' => 'lesson_progress.update', 'uses' => 'LessonProgressController@update']);
    Route::delete('/delete/{id}', ['as' => 'lesson_progress.destroy', 'uses' => 'LessonProgressController@destroy']);
    
});
