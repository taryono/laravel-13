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
Route::group(['prefix' => 'dapodik-sync-schedule', 'namespace' => 'App\Modules\DapodikSyncSchedule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dapodik_sync_schedule.index', 'uses' => 'DapodikSyncScheduleController@index']);
    Route::any('/search', ['as' => 'dapodik_sync_schedule.search', 'uses' => 'DapodikSyncScheduleController@search']);
    Route::get('/create', ['as' => 'dapodik_sync_schedule.create', 'uses' => 'DapodikSyncScheduleController@create']);
    Route::get('/show/{id}', ['as' => 'dapodik_sync_schedule.show', 'uses' => 'DapodikSyncScheduleController@show']);
    Route::get('/preview/{id}', ['as' => 'dapodik_sync_schedule.preview', 'uses' => 'DapodikSyncScheduleController@preview']);
    Route::get('/print/{id}', ['as' => 'dapodik_sync_schedule.print', 'uses' => 'DapodikSyncScheduleController@print']);

    Route::post('/store', ['as' => 'dapodik_sync_schedule.store', 'uses' => 'DapodikSyncScheduleController@store']);
    Route::get('/edit/{id}', ['as' => 'dapodik_sync_schedule.edit', 'uses' => 'DapodikSyncScheduleController@edit']);
    Route::put('/update/{id}', ['as' => 'dapodik_sync_schedule.update', 'uses' => 'DapodikSyncScheduleController@update']);
    Route::delete('/delete/{id}', ['as' => 'dapodik_sync_schedule.destroy', 'uses' => 'DapodikSyncScheduleController@destroy']);
    
});
