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
Route::group(['prefix' => 'teacher-comment', 'namespace' => 'App\Modules\TeacherComment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'teacher_comment.index', 'uses' => 'TeacherCommentController@index']);
    Route::any('/search', ['as' => 'teacher_comment.search', 'uses' => 'TeacherCommentController@search']);
    Route::get('/create', ['as' => 'teacher_comment.create', 'uses' => 'TeacherCommentController@create']);
    Route::get('/show/{id}', ['as' => 'teacher_comment.show', 'uses' => 'TeacherCommentController@show']);
    Route::get('/preview/{id}', ['as' => 'teacher_comment.preview', 'uses' => 'TeacherCommentController@preview']);
    Route::get('/print/{id}', ['as' => 'teacher_comment.print', 'uses' => 'TeacherCommentController@print']);

    Route::post('/store', ['as' => 'teacher_comment.store', 'uses' => 'TeacherCommentController@store']);
    Route::get('/edit/{id}', ['as' => 'teacher_comment.edit', 'uses' => 'TeacherCommentController@edit']);
    Route::put('/update/{id}', ['as' => 'teacher_comment.update', 'uses' => 'TeacherCommentController@update']);
    Route::delete('/delete/{id}', ['as' => 'teacher_comment.destroy', 'uses' => 'TeacherCommentController@destroy']);
    
});
