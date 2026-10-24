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
Route::group(['prefix' => 'question-media-annotation', 'namespace' => 'App\Modules\QuestionMediaAnnotation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'question_media_annotation.index', 'uses' => 'QuestionMediaAnnotationController@index']);
    Route::any('/search', ['as' => 'question_media_annotation.search', 'uses' => 'QuestionMediaAnnotationController@search']);
    Route::get('/create', ['as' => 'question_media_annotation.create', 'uses' => 'QuestionMediaAnnotationController@create']);
    Route::get('/show/{id}', ['as' => 'question_media_annotation.show', 'uses' => 'QuestionMediaAnnotationController@show']);
    Route::get('/preview/{id}', ['as' => 'question_media_annotation.preview', 'uses' => 'QuestionMediaAnnotationController@preview']);
    Route::get('/print/{id}', ['as' => 'question_media_annotation.print', 'uses' => 'QuestionMediaAnnotationController@print']);

    Route::post('/store', ['as' => 'question_media_annotation.store', 'uses' => 'QuestionMediaAnnotationController@store']);
    Route::get('/edit/{id}', ['as' => 'question_media_annotation.edit', 'uses' => 'QuestionMediaAnnotationController@edit']);
    Route::put('/update/{id}', ['as' => 'question_media_annotation.update', 'uses' => 'QuestionMediaAnnotationController@update']);
    Route::delete('/delete/{id}', ['as' => 'question_media_annotation.destroy', 'uses' => 'QuestionMediaAnnotationController@destroy']);
    
});
