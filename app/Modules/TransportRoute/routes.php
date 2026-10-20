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
Route::group(['prefix' => 'transport-route', 'namespace' => 'App\Modules\TransportRoute\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'transport_route.index', 'uses' => 'TransportRouteController@index']);
    Route::any('/search', ['as' => 'transport_route.search', 'uses' => 'TransportRouteController@search']);
    Route::get('/create', ['as' => 'transport_route.create', 'uses' => 'TransportRouteController@create']);
    Route::get('/show/{id}', ['as' => 'transport_route.show', 'uses' => 'TransportRouteController@show']);
    Route::get('/preview/{id}', ['as' => 'transport_route.preview', 'uses' => 'TransportRouteController@preview']);
    Route::get('/print/{id}', ['as' => 'transport_route.print', 'uses' => 'TransportRouteController@print']);

    Route::post('/store', ['as' => 'transport_route.store', 'uses' => 'TransportRouteController@store']);
    Route::get('/edit/{id}', ['as' => 'transport_route.edit', 'uses' => 'TransportRouteController@edit']);
    Route::put('/update/{id}', ['as' => 'transport_route.update', 'uses' => 'TransportRouteController@update']);
    Route::delete('/delete/{id}', ['as' => 'transport_route.destroy', 'uses' => 'TransportRouteController@destroy']);
    
});
