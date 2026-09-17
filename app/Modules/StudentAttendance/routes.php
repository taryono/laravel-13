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
Route::group(['prefix' => 'student-attendance', 'namespace' => 'App\Modules\StudentAttendance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_attendance.index', 'uses' => 'StudentAttendanceController@index']);
    Route::any('/search', ['as' => 'student_attendance.search', 'uses' => 'StudentAttendanceController@search']);
    Route::get('/create', ['as' => 'student_attendance.create', 'uses' => 'StudentAttendanceController@create']);
    Route::get('/show/{id}', ['as' => 'student_attendance.show', 'uses' => 'StudentAttendanceController@show']);
    Route::get('/preview/{id}', ['as' => 'student_attendance.preview', 'uses' => 'StudentAttendanceController@preview']);
    Route::get('/print/{id}', ['as' => 'student_attendance.print', 'uses' => 'StudentAttendanceController@print']);

    Route::post('/store', ['as' => 'student_attendance.store', 'uses' => 'StudentAttendanceController@store']);
    Route::get('/edit/{id}', ['as' => 'student_attendance.edit', 'uses' => 'StudentAttendanceController@edit']);
    Route::put('/update/{id}', ['as' => 'student_attendance.update', 'uses' => 'StudentAttendanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_attendance.destroy', 'uses' => 'StudentAttendanceController@destroy']);
    
});
