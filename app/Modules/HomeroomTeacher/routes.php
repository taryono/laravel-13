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
Route::group(['prefix' => 'homeroom-teacher', 'namespace' => 'App\Modules\HomeroomTeacher\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'homeroom_teacher.index', 'uses' => 'HomeroomTeacherController@index']);
    Route::any('/search', ['as' => 'homeroom_teacher.search', 'uses' => 'HomeroomTeacherController@search']);
    Route::get('/create', ['as' => 'homeroom_teacher.create', 'uses' => 'HomeroomTeacherController@create']);
    Route::get('/show/{id}', ['as' => 'homeroom_teacher.show', 'uses' => 'HomeroomTeacherController@show']);
    Route::get('/preview/{id}', ['as' => 'homeroom_teacher.preview', 'uses' => 'HomeroomTeacherController@preview']);
    Route::get('/print/{id}', ['as' => 'homeroom_teacher.print', 'uses' => 'HomeroomTeacherController@print']);

    Route::post('/store', ['as' => 'homeroom_teacher.store', 'uses' => 'HomeroomTeacherController@store']);
    Route::get('/edit/{id}', ['as' => 'homeroom_teacher.edit', 'uses' => 'HomeroomTeacherController@edit']);
    Route::put('/update/{id}', ['as' => 'homeroom_teacher.update', 'uses' => 'HomeroomTeacherController@update']);
    Route::delete('/delete/{id}', ['as' => 'homeroom_teacher.destroy', 'uses' => 'HomeroomTeacherController@destroy']);
    
});
