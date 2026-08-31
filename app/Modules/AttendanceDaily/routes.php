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
Route::group(['prefix' => 'attendance-daily', 'namespace' => 'App\Modules\AttendanceDaily\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attendance_daily.index', 'uses' => 'AttendanceDailyController@index']);
    Route::any('/search', ['as' => 'attendance_daily.search', 'uses' => 'AttendanceDailyController@search']);
    Route::get('/create', ['as' => 'attendance_daily.create', 'uses' => 'AttendanceDailyController@create']);
    Route::get('/show/{id}', ['as' => 'attendance_daily.show', 'uses' => 'AttendanceDailyController@show']);
    Route::get('/preview/{id}', ['as' => 'attendance_daily.preview', 'uses' => 'AttendanceDailyController@preview']);
    Route::get('/print/{id}', ['as' => 'attendance_daily.print', 'uses' => 'AttendanceDailyController@print']);

    Route::post('/store', ['as' => 'attendance_daily.store', 'uses' => 'AttendanceDailyController@store']);
    Route::get('/edit/{id}', ['as' => 'attendance_daily.edit', 'uses' => 'AttendanceDailyController@edit']);
    Route::put('/update/{id}', ['as' => 'attendance_daily.update', 'uses' => 'AttendanceDailyController@update']);
    Route::delete('/delete/{id}', ['as' => 'attendance_daily.destroy', 'uses' => 'AttendanceDailyController@destroy']);
    
});
