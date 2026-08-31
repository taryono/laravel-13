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
Route::group(['prefix' => 'attendance-log', 'namespace' => 'App\Modules\AttendanceLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attendance_log.index', 'uses' => 'AttendanceLogController@index']);
    Route::any('/search', ['as' => 'attendance_log.search', 'uses' => 'AttendanceLogController@search']);
    Route::get('/create', ['as' => 'attendance_log.create', 'uses' => 'AttendanceLogController@create']);
    Route::get('/show/{id}', ['as' => 'attendance_log.show', 'uses' => 'AttendanceLogController@show']);
    Route::get('/preview/{id}', ['as' => 'attendance_log.preview', 'uses' => 'AttendanceLogController@preview']);
    Route::get('/print/{id}', ['as' => 'attendance_log.print', 'uses' => 'AttendanceLogController@print']);

    Route::post('/store', ['as' => 'attendance_log.store', 'uses' => 'AttendanceLogController@store']);
    Route::get('/edit/{id}', ['as' => 'attendance_log.edit', 'uses' => 'AttendanceLogController@edit']);
    Route::put('/update/{id}', ['as' => 'attendance_log.update', 'uses' => 'AttendanceLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'attendance_log.destroy', 'uses' => 'AttendanceLogController@destroy']);
    
});
