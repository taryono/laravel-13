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
Route::group(['prefix' => 'extracurricular-attendance', 'namespace' => 'App\Modules\ExtracurricularAttendance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'extracurricular_attendance.index', 'uses' => 'ExtracurricularAttendanceController@index']);
    Route::any('/search', ['as' => 'extracurricular_attendance.search', 'uses' => 'ExtracurricularAttendanceController@search']);
    Route::get('/create', ['as' => 'extracurricular_attendance.create', 'uses' => 'ExtracurricularAttendanceController@create']);
    Route::get('/show/{id}', ['as' => 'extracurricular_attendance.show', 'uses' => 'ExtracurricularAttendanceController@show']);
    Route::get('/preview/{id}', ['as' => 'extracurricular_attendance.preview', 'uses' => 'ExtracurricularAttendanceController@preview']);
    Route::get('/print/{id}', ['as' => 'extracurricular_attendance.print', 'uses' => 'ExtracurricularAttendanceController@print']);

    Route::post('/store', ['as' => 'extracurricular_attendance.store', 'uses' => 'ExtracurricularAttendanceController@store']);
    Route::get('/edit/{id}', ['as' => 'extracurricular_attendance.edit', 'uses' => 'ExtracurricularAttendanceController@edit']);
    Route::put('/update/{id}', ['as' => 'extracurricular_attendance.update', 'uses' => 'ExtracurricularAttendanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'extracurricular_attendance.destroy', 'uses' => 'ExtracurricularAttendanceController@destroy']);
    
});
