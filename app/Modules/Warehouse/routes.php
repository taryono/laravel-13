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
Route::group(['prefix' => 'warehouse', 'namespace' => 'App\Modules\Warehouse\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'warehouse.index', 'uses' => 'WarehouseController@index']);
    Route::any('/search', ['as' => 'warehouse.search', 'uses' => 'WarehouseController@search']);
    Route::get('/create', ['as' => 'warehouse.create', 'uses' => 'WarehouseController@create']);
    Route::get('/show/{id}', ['as' => 'warehouse.show', 'uses' => 'WarehouseController@show']);
    Route::get('/preview/{id}', ['as' => 'warehouse.preview', 'uses' => 'WarehouseController@preview']);
    Route::get('/print/{id}', ['as' => 'warehouse.print', 'uses' => 'WarehouseController@print']);

    Route::post('/store', ['as' => 'warehouse.store', 'uses' => 'WarehouseController@store']);
    Route::get('/edit/{id}', ['as' => 'warehouse.edit', 'uses' => 'WarehouseController@edit']);
    Route::put('/update/{id}', ['as' => 'warehouse.update', 'uses' => 'WarehouseController@update']);
    Route::delete('/delete/{id}', ['as' => 'warehouse.destroy', 'uses' => 'WarehouseController@destroy']);
    
});
