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
Route::group(['prefix' => 'gps-telemetry-log', 'namespace' => 'App\Modules\GpsTelemetryLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'gps_telemetry_log.index', 'uses' => 'GpsTelemetryLogController@index']);
    Route::any('/search', ['as' => 'gps_telemetry_log.search', 'uses' => 'GpsTelemetryLogController@search']);
    Route::get('/create', ['as' => 'gps_telemetry_log.create', 'uses' => 'GpsTelemetryLogController@create']);
    Route::get('/show/{id}', ['as' => 'gps_telemetry_log.show', 'uses' => 'GpsTelemetryLogController@show']);
    Route::get('/preview/{id}', ['as' => 'gps_telemetry_log.preview', 'uses' => 'GpsTelemetryLogController@preview']);
    Route::get('/print/{id}', ['as' => 'gps_telemetry_log.print', 'uses' => 'GpsTelemetryLogController@print']);

    Route::post('/store', ['as' => 'gps_telemetry_log.store', 'uses' => 'GpsTelemetryLogController@store']);
    Route::get('/edit/{id}', ['as' => 'gps_telemetry_log.edit', 'uses' => 'GpsTelemetryLogController@edit']);
    Route::put('/update/{id}', ['as' => 'gps_telemetry_log.update', 'uses' => 'GpsTelemetryLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'gps_telemetry_log.destroy', 'uses' => 'GpsTelemetryLogController@destroy']);
    
});
