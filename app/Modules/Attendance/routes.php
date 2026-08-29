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
Route::group(['prefix' => 'attendance', 'namespace' => 'App\Modules\Attendance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attendance.index', 'uses' => 'AttendanceController@index']);
    Route::any('/search', ['as' => 'attendance.search', 'uses' => 'AttendanceController@search']);
    Route::get('/create', ['as' => 'attendance.create', 'uses' => 'AttendanceController@create']);
    Route::get('/show/{id}', ['as' => 'attendance.show', 'uses' => 'AttendanceController@show']);
    Route::get('/preview/{id}', ['as' => 'attendance.preview', 'uses' => 'AttendanceController@preview']);
    Route::get('/print/{id}', ['as' => 'attendance.print', 'uses' => 'AttendanceController@print']);

    Route::post('/store', ['as' => 'attendance.store', 'uses' => 'AttendanceController@store']);
    Route::get('/edit/{id}', ['as' => 'attendance.edit', 'uses' => 'AttendanceController@edit']);
    Route::put('/update/{id}', ['as' => 'attendance.update', 'uses' => 'AttendanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'attendance.destroy', 'uses' => 'AttendanceController@destroy']);
    
});
