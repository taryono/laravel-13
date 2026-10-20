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
Route::group(['prefix' => 'vehicle-maintenance-log', 'namespace' => 'App\Modules\VehicleMaintenanceLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'vehicle_maintenance_log.index', 'uses' => 'VehicleMaintenanceLogController@index']);
    Route::any('/search', ['as' => 'vehicle_maintenance_log.search', 'uses' => 'VehicleMaintenanceLogController@search']);
    Route::get('/create', ['as' => 'vehicle_maintenance_log.create', 'uses' => 'VehicleMaintenanceLogController@create']);
    Route::get('/show/{id}', ['as' => 'vehicle_maintenance_log.show', 'uses' => 'VehicleMaintenanceLogController@show']);
    Route::get('/preview/{id}', ['as' => 'vehicle_maintenance_log.preview', 'uses' => 'VehicleMaintenanceLogController@preview']);
    Route::get('/print/{id}', ['as' => 'vehicle_maintenance_log.print', 'uses' => 'VehicleMaintenanceLogController@print']);

    Route::post('/store', ['as' => 'vehicle_maintenance_log.store', 'uses' => 'VehicleMaintenanceLogController@store']);
    Route::get('/edit/{id}', ['as' => 'vehicle_maintenance_log.edit', 'uses' => 'VehicleMaintenanceLogController@edit']);
    Route::put('/update/{id}', ['as' => 'vehicle_maintenance_log.update', 'uses' => 'VehicleMaintenanceLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'vehicle_maintenance_log.destroy', 'uses' => 'VehicleMaintenanceLogController@destroy']);
    
});
