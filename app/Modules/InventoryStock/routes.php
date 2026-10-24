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
Route::group(['prefix' => 'inventory-stock', 'namespace' => 'App\Modules\InventoryStock\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'inventory_stock.index', 'uses' => 'InventoryStockController@index']);
    Route::any('/search', ['as' => 'inventory_stock.search', 'uses' => 'InventoryStockController@search']);
    Route::get('/create', ['as' => 'inventory_stock.create', 'uses' => 'InventoryStockController@create']);
    Route::get('/show/{id}', ['as' => 'inventory_stock.show', 'uses' => 'InventoryStockController@show']);
    Route::get('/preview/{id}', ['as' => 'inventory_stock.preview', 'uses' => 'InventoryStockController@preview']);
    Route::get('/print/{id}', ['as' => 'inventory_stock.print', 'uses' => 'InventoryStockController@print']);

    Route::post('/store', ['as' => 'inventory_stock.store', 'uses' => 'InventoryStockController@store']);
    Route::get('/edit/{id}', ['as' => 'inventory_stock.edit', 'uses' => 'InventoryStockController@edit']);
    Route::put('/update/{id}', ['as' => 'inventory_stock.update', 'uses' => 'InventoryStockController@update']);
    Route::delete('/delete/{id}', ['as' => 'inventory_stock.destroy', 'uses' => 'InventoryStockController@destroy']);
    
});
