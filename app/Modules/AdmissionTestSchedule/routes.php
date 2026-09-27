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
Route::group(['prefix' => 'admission-test-schedule', 'namespace' => 'App\Modules\AdmissionTestSchedule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_test_schedule.index', 'uses' => 'AdmissionTestScheduleController@index']);
    Route::any('/search', ['as' => 'admission_test_schedule.search', 'uses' => 'AdmissionTestScheduleController@search']);
    Route::get('/create', ['as' => 'admission_test_schedule.create', 'uses' => 'AdmissionTestScheduleController@create']);
    Route::get('/show/{id}', ['as' => 'admission_test_schedule.show', 'uses' => 'AdmissionTestScheduleController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_test_schedule.preview', 'uses' => 'AdmissionTestScheduleController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_test_schedule.print', 'uses' => 'AdmissionTestScheduleController@print']);

    Route::post('/store', ['as' => 'admission_test_schedule.store', 'uses' => 'AdmissionTestScheduleController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_test_schedule.edit', 'uses' => 'AdmissionTestScheduleController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_test_schedule.update', 'uses' => 'AdmissionTestScheduleController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_test_schedule.destroy', 'uses' => 'AdmissionTestScheduleController@destroy']);
    
});
