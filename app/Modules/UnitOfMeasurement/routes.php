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
Route::group(['prefix' => 'unit-of-measurement', 'namespace' => 'App\Modules\UnitOfMeasurement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'unit_of_measurement.index', 'uses' => 'UnitOfMeasurementController@index']);
    Route::any('/search', ['as' => 'unit_of_measurement.search', 'uses' => 'UnitOfMeasurementController@search']);
    Route::get('/create', ['as' => 'unit_of_measurement.create', 'uses' => 'UnitOfMeasurementController@create']);
    Route::get('/show/{id}', ['as' => 'unit_of_measurement.show', 'uses' => 'UnitOfMeasurementController@show']);
    Route::get('/preview/{id}', ['as' => 'unit_of_measurement.preview', 'uses' => 'UnitOfMeasurementController@preview']);
    Route::get('/print/{id}', ['as' => 'unit_of_measurement.print', 'uses' => 'UnitOfMeasurementController@print']);

    Route::post('/store', ['as' => 'unit_of_measurement.store', 'uses' => 'UnitOfMeasurementController@store']);
    Route::get('/edit/{id}', ['as' => 'unit_of_measurement.edit', 'uses' => 'UnitOfMeasurementController@edit']);
    Route::put('/update/{id}', ['as' => 'unit_of_measurement.update', 'uses' => 'UnitOfMeasurementController@update']);
    Route::delete('/delete/{id}', ['as' => 'unit_of_measurement.destroy', 'uses' => 'UnitOfMeasurementController@destroy']);
    
});
