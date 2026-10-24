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
Route::group(['prefix' => 'work-schedule', 'namespace' => 'App\Modules\WorkSchedule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'work_schedule.index', 'uses' => 'WorkScheduleController@index']);
    Route::any('/search', ['as' => 'work_schedule.search', 'uses' => 'WorkScheduleController@search']);
    Route::get('/create', ['as' => 'work_schedule.create', 'uses' => 'WorkScheduleController@create']);
    Route::get('/show/{id}', ['as' => 'work_schedule.show', 'uses' => 'WorkScheduleController@show']);
    Route::get('/preview/{id}', ['as' => 'work_schedule.preview', 'uses' => 'WorkScheduleController@preview']);
    Route::get('/print/{id}', ['as' => 'work_schedule.print', 'uses' => 'WorkScheduleController@print']);

    Route::post('/store', ['as' => 'work_schedule.store', 'uses' => 'WorkScheduleController@store']);
    Route::get('/edit/{id}', ['as' => 'work_schedule.edit', 'uses' => 'WorkScheduleController@edit']);
    Route::put('/update/{id}', ['as' => 'work_schedule.update', 'uses' => 'WorkScheduleController@update']);
    Route::delete('/delete/{id}', ['as' => 'work_schedule.destroy', 'uses' => 'WorkScheduleController@destroy']);
    
});
