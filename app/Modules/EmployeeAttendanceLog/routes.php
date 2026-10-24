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
Route::group(['prefix' => 'employee-attendance-log', 'namespace' => 'App\Modules\EmployeeAttendanceLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_attendance_log.index', 'uses' => 'EmployeeAttendanceLogController@index']);
    Route::any('/search', ['as' => 'employee_attendance_log.search', 'uses' => 'EmployeeAttendanceLogController@search']);
    Route::get('/create', ['as' => 'employee_attendance_log.create', 'uses' => 'EmployeeAttendanceLogController@create']);
    Route::get('/show/{id}', ['as' => 'employee_attendance_log.show', 'uses' => 'EmployeeAttendanceLogController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_attendance_log.preview', 'uses' => 'EmployeeAttendanceLogController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_attendance_log.print', 'uses' => 'EmployeeAttendanceLogController@print']);

    Route::post('/store', ['as' => 'employee_attendance_log.store', 'uses' => 'EmployeeAttendanceLogController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_attendance_log.edit', 'uses' => 'EmployeeAttendanceLogController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_attendance_log.update', 'uses' => 'EmployeeAttendanceLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_attendance_log.destroy', 'uses' => 'EmployeeAttendanceLogController@destroy']);
    
});
