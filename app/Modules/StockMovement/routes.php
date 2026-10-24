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
Route::group(['prefix' => 'stock-movement', 'namespace' => 'App\Modules\StockMovement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'stock_movement.index', 'uses' => 'StockMovementController@index']);
    Route::any('/search', ['as' => 'stock_movement.search', 'uses' => 'StockMovementController@search']);
    Route::get('/create', ['as' => 'stock_movement.create', 'uses' => 'StockMovementController@create']);
    Route::get('/show/{id}', ['as' => 'stock_movement.show', 'uses' => 'StockMovementController@show']);
    Route::get('/preview/{id}', ['as' => 'stock_movement.preview', 'uses' => 'StockMovementController@preview']);
    Route::get('/print/{id}', ['as' => 'stock_movement.print', 'uses' => 'StockMovementController@print']);

    Route::post('/store', ['as' => 'stock_movement.store', 'uses' => 'StockMovementController@store']);
    Route::get('/edit/{id}', ['as' => 'stock_movement.edit', 'uses' => 'StockMovementController@edit']);
    Route::put('/update/{id}', ['as' => 'stock_movement.update', 'uses' => 'StockMovementController@update']);
    Route::delete('/delete/{id}', ['as' => 'stock_movement.destroy', 'uses' => 'StockMovementController@destroy']);
    
});
