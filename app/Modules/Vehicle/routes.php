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
Route::group(['prefix' => 'vehicle', 'namespace' => 'App\Modules\Vehicle\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'vehicle.index', 'uses' => 'VehicleController@index']);
    Route::any('/search', ['as' => 'vehicle.search', 'uses' => 'VehicleController@search']);
    Route::get('/create', ['as' => 'vehicle.create', 'uses' => 'VehicleController@create']);
    Route::get('/show/{id}', ['as' => 'vehicle.show', 'uses' => 'VehicleController@show']);
    Route::get('/preview/{id}', ['as' => 'vehicle.preview', 'uses' => 'VehicleController@preview']);
    Route::get('/print/{id}', ['as' => 'vehicle.print', 'uses' => 'VehicleController@print']);

    Route::post('/store', ['as' => 'vehicle.store', 'uses' => 'VehicleController@store']);
    Route::get('/edit/{id}', ['as' => 'vehicle.edit', 'uses' => 'VehicleController@edit']);
    Route::put('/update/{id}', ['as' => 'vehicle.update', 'uses' => 'VehicleController@update']);
    Route::delete('/delete/{id}', ['as' => 'vehicle.destroy', 'uses' => 'VehicleController@destroy']);
    
});
