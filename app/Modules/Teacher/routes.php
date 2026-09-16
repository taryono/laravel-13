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
Route::group(['prefix' => 'teacher', 'namespace' => 'App\Modules\Teacher\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'teacher.index', 'uses' => 'TeacherController@index']);
    Route::any('/search', ['as' => 'teacher.search', 'uses' => 'TeacherController@search']);
    Route::get('/create', ['as' => 'teacher.create', 'uses' => 'TeacherController@create']);
    Route::get('/show/{id}', ['as' => 'teacher.show', 'uses' => 'TeacherController@show']);
    Route::get('/preview/{id}', ['as' => 'teacher.preview', 'uses' => 'TeacherController@preview']);
    Route::get('/print/{id}', ['as' => 'teacher.print', 'uses' => 'TeacherController@print']);

    Route::post('/store', ['as' => 'teacher.store', 'uses' => 'TeacherController@store']);
    Route::get('/edit/{id}', ['as' => 'teacher.edit', 'uses' => 'TeacherController@edit']);
    Route::put('/update/{id}', ['as' => 'teacher.update', 'uses' => 'TeacherController@update']);
    Route::delete('/delete/{id}', ['as' => 'teacher.destroy', 'uses' => 'TeacherController@destroy']);
    
});
