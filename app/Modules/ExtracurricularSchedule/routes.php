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
Route::group(['prefix' => 'extracurricular-schedule', 'namespace' => 'App\Modules\ExtracurricularSchedule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'extracurricular_schedule.index', 'uses' => 'ExtracurricularScheduleController@index']);
    Route::any('/search', ['as' => 'extracurricular_schedule.search', 'uses' => 'ExtracurricularScheduleController@search']);
    Route::get('/create', ['as' => 'extracurricular_schedule.create', 'uses' => 'ExtracurricularScheduleController@create']);
    Route::get('/show/{id}', ['as' => 'extracurricular_schedule.show', 'uses' => 'ExtracurricularScheduleController@show']);
    Route::get('/preview/{id}', ['as' => 'extracurricular_schedule.preview', 'uses' => 'ExtracurricularScheduleController@preview']);
    Route::get('/print/{id}', ['as' => 'extracurricular_schedule.print', 'uses' => 'ExtracurricularScheduleController@print']);

    Route::post('/store', ['as' => 'extracurricular_schedule.store', 'uses' => 'ExtracurricularScheduleController@store']);
    Route::get('/edit/{id}', ['as' => 'extracurricular_schedule.edit', 'uses' => 'ExtracurricularScheduleController@edit']);
    Route::put('/update/{id}', ['as' => 'extracurricular_schedule.update', 'uses' => 'ExtracurricularScheduleController@update']);
    Route::delete('/delete/{id}', ['as' => 'extracurricular_schedule.destroy', 'uses' => 'ExtracurricularScheduleController@destroy']);
    
});
