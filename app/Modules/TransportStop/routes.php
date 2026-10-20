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
Route::group(['prefix' => 'transport-stop', 'namespace' => 'App\Modules\TransportStop\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'transport_stop.index', 'uses' => 'TransportStopController@index']);
    Route::any('/search', ['as' => 'transport_stop.search', 'uses' => 'TransportStopController@search']);
    Route::get('/create', ['as' => 'transport_stop.create', 'uses' => 'TransportStopController@create']);
    Route::get('/show/{id}', ['as' => 'transport_stop.show', 'uses' => 'TransportStopController@show']);
    Route::get('/preview/{id}', ['as' => 'transport_stop.preview', 'uses' => 'TransportStopController@preview']);
    Route::get('/print/{id}', ['as' => 'transport_stop.print', 'uses' => 'TransportStopController@print']);

    Route::post('/store', ['as' => 'transport_stop.store', 'uses' => 'TransportStopController@store']);
    Route::get('/edit/{id}', ['as' => 'transport_stop.edit', 'uses' => 'TransportStopController@edit']);
    Route::put('/update/{id}', ['as' => 'transport_stop.update', 'uses' => 'TransportStopController@update']);
    Route::delete('/delete/{id}', ['as' => 'transport_stop.destroy', 'uses' => 'TransportStopController@destroy']);
    
});
