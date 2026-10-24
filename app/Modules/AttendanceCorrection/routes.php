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
Route::group(['prefix' => 'attendance-correction', 'namespace' => 'App\Modules\AttendanceCorrection\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attendance_correction.index', 'uses' => 'AttendanceCorrectionController@index']);
    Route::any('/search', ['as' => 'attendance_correction.search', 'uses' => 'AttendanceCorrectionController@search']);
    Route::get('/create', ['as' => 'attendance_correction.create', 'uses' => 'AttendanceCorrectionController@create']);
    Route::get('/show/{id}', ['as' => 'attendance_correction.show', 'uses' => 'AttendanceCorrectionController@show']);
    Route::get('/preview/{id}', ['as' => 'attendance_correction.preview', 'uses' => 'AttendanceCorrectionController@preview']);
    Route::get('/print/{id}', ['as' => 'attendance_correction.print', 'uses' => 'AttendanceCorrectionController@print']);

    Route::post('/store', ['as' => 'attendance_correction.store', 'uses' => 'AttendanceCorrectionController@store']);
    Route::get('/edit/{id}', ['as' => 'attendance_correction.edit', 'uses' => 'AttendanceCorrectionController@edit']);
    Route::put('/update/{id}', ['as' => 'attendance_correction.update', 'uses' => 'AttendanceCorrectionController@update']);
    Route::delete('/delete/{id}', ['as' => 'attendance_correction.destroy', 'uses' => 'AttendanceCorrectionController@destroy']);
    
});
