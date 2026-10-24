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
Route::group(['prefix' => 'employee-attendance', 'namespace' => 'App\Modules\EmployeeAttendance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'employee_attendance.index', 'uses' => 'EmployeeAttendanceController@index']);
    Route::any('/search', ['as' => 'employee_attendance.search', 'uses' => 'EmployeeAttendanceController@search']);
    Route::get('/create', ['as' => 'employee_attendance.create', 'uses' => 'EmployeeAttendanceController@create']);
    Route::get('/show/{id}', ['as' => 'employee_attendance.show', 'uses' => 'EmployeeAttendanceController@show']);
    Route::get('/preview/{id}', ['as' => 'employee_attendance.preview', 'uses' => 'EmployeeAttendanceController@preview']);
    Route::get('/print/{id}', ['as' => 'employee_attendance.print', 'uses' => 'EmployeeAttendanceController@print']);

    Route::post('/store', ['as' => 'employee_attendance.store', 'uses' => 'EmployeeAttendanceController@store']);
    Route::get('/edit/{id}', ['as' => 'employee_attendance.edit', 'uses' => 'EmployeeAttendanceController@edit']);
    Route::put('/update/{id}', ['as' => 'employee_attendance.update', 'uses' => 'EmployeeAttendanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'employee_attendance.destroy', 'uses' => 'EmployeeAttendanceController@destroy']);
    
});
