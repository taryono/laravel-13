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
Route::group(['prefix' => 'attendance-import-batch', 'namespace' => 'App\Modules\AttendanceImportBatch\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attendance_import_batch.index', 'uses' => 'AttendanceImportBatchController@index']);
    Route::any('/search', ['as' => 'attendance_import_batch.search', 'uses' => 'AttendanceImportBatchController@search']);
    Route::get('/create', ['as' => 'attendance_import_batch.create', 'uses' => 'AttendanceImportBatchController@create']);
    Route::get('/show/{id}', ['as' => 'attendance_import_batch.show', 'uses' => 'AttendanceImportBatchController@show']);
    Route::get('/preview/{id}', ['as' => 'attendance_import_batch.preview', 'uses' => 'AttendanceImportBatchController@preview']);
    Route::get('/print/{id}', ['as' => 'attendance_import_batch.print', 'uses' => 'AttendanceImportBatchController@print']);

    Route::post('/store', ['as' => 'attendance_import_batch.store', 'uses' => 'AttendanceImportBatchController@store']);
    Route::get('/edit/{id}', ['as' => 'attendance_import_batch.edit', 'uses' => 'AttendanceImportBatchController@edit']);
    Route::put('/update/{id}', ['as' => 'attendance_import_batch.update', 'uses' => 'AttendanceImportBatchController@update']);
    Route::delete('/delete/{id}', ['as' => 'attendance_import_batch.destroy', 'uses' => 'AttendanceImportBatchController@destroy']);
    
});
