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
Route::group(['prefix' => 'attendance-import', 'namespace' => 'App\Modules\AttendanceImport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attendance_import.index', 'uses' => 'AttendanceImportController@index']);
    Route::any('/search', ['as' => 'attendance_import.search', 'uses' => 'AttendanceImportController@search']);
    Route::get('/create', ['as' => 'attendance_import.create', 'uses' => 'AttendanceImportController@create']);
    Route::get('/show/{id}', ['as' => 'attendance_import.show', 'uses' => 'AttendanceImportController@show']);
    Route::get('/preview/{id}', ['as' => 'attendance_import.preview', 'uses' => 'AttendanceImportController@preview']);
    Route::get('/print/{id}', ['as' => 'attendance_import.print', 'uses' => 'AttendanceImportController@print']);

    Route::post('/store', ['as' => 'attendance_import.store', 'uses' => 'AttendanceImportController@store']);
    Route::get('/edit/{id}', ['as' => 'attendance_import.edit', 'uses' => 'AttendanceImportController@edit']);
    Route::put('/update/{id}', ['as' => 'attendance_import.update', 'uses' => 'AttendanceImportController@update']);
    Route::delete('/delete/{id}', ['as' => 'attendance_import.destroy', 'uses' => 'AttendanceImportController@destroy']);
    
});
