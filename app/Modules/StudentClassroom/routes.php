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
Route::group(['prefix' => 'student-classroom', 'namespace' => 'App\Modules\StudentClassroom\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_classroom.index', 'uses' => 'StudentClassroomController@index']);
    Route::any('/search', ['as' => 'student_classroom.search', 'uses' => 'StudentClassroomController@search']);
    Route::get('/create', ['as' => 'student_classroom.create', 'uses' => 'StudentClassroomController@create']);
    Route::get('/show/{id}', ['as' => 'student_classroom.show', 'uses' => 'StudentClassroomController@show']);
    Route::get('/preview/{id}', ['as' => 'student_classroom.preview', 'uses' => 'StudentClassroomController@preview']);
    Route::get('/print/{id}', ['as' => 'student_classroom.print', 'uses' => 'StudentClassroomController@print']);

    Route::post('/store', ['as' => 'student_classroom.store', 'uses' => 'StudentClassroomController@store']);
    Route::get('/edit/{id}', ['as' => 'student_classroom.edit', 'uses' => 'StudentClassroomController@edit']);
    Route::put('/update/{id}', ['as' => 'student_classroom.update', 'uses' => 'StudentClassroomController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_classroom.destroy', 'uses' => 'StudentClassroomController@destroy']);
    
});
