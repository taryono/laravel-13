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
Route::group(['prefix' => 'driver', 'namespace' => 'App\Modules\Driver\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'driver.index', 'uses' => 'DriverController@index']);
    Route::any('/search', ['as' => 'driver.search', 'uses' => 'DriverController@search']);
    Route::get('/create', ['as' => 'driver.create', 'uses' => 'DriverController@create']);
    Route::get('/show/{id}', ['as' => 'driver.show', 'uses' => 'DriverController@show']);
    Route::get('/preview/{id}', ['as' => 'driver.preview', 'uses' => 'DriverController@preview']);
    Route::get('/print/{id}', ['as' => 'driver.print', 'uses' => 'DriverController@print']);

    Route::post('/store', ['as' => 'driver.store', 'uses' => 'DriverController@store']);
    Route::get('/edit/{id}', ['as' => 'driver.edit', 'uses' => 'DriverController@edit']);
    Route::put('/update/{id}', ['as' => 'driver.update', 'uses' => 'DriverController@update']);
    Route::delete('/delete/{id}', ['as' => 'driver.destroy', 'uses' => 'DriverController@destroy']);
    
});
