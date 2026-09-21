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
Route::group(['prefix' => 'student-health-record', 'namespace' => 'App\Modules\StudentHealthRecord\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_health_record.index', 'uses' => 'StudentHealthRecordController@index']);
    Route::any('/search', ['as' => 'student_health_record.search', 'uses' => 'StudentHealthRecordController@search']);
    Route::get('/create', ['as' => 'student_health_record.create', 'uses' => 'StudentHealthRecordController@create']);
    Route::get('/show/{id}', ['as' => 'student_health_record.show', 'uses' => 'StudentHealthRecordController@show']);
    Route::get('/preview/{id}', ['as' => 'student_health_record.preview', 'uses' => 'StudentHealthRecordController@preview']);
    Route::get('/print/{id}', ['as' => 'student_health_record.print', 'uses' => 'StudentHealthRecordController@print']);

    Route::post('/store', ['as' => 'student_health_record.store', 'uses' => 'StudentHealthRecordController@store']);
    Route::get('/edit/{id}', ['as' => 'student_health_record.edit', 'uses' => 'StudentHealthRecordController@edit']);
    Route::put('/update/{id}', ['as' => 'student_health_record.update', 'uses' => 'StudentHealthRecordController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_health_record.destroy', 'uses' => 'StudentHealthRecordController@destroy']);
    
});
