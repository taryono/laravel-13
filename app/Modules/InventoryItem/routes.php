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
Route::group(['prefix' => 'inventory-item', 'namespace' => 'App\Modules\InventoryItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'inventory_item.index', 'uses' => 'InventoryItemController@index']);
    Route::any('/search', ['as' => 'inventory_item.search', 'uses' => 'InventoryItemController@search']);
    Route::get('/create', ['as' => 'inventory_item.create', 'uses' => 'InventoryItemController@create']);
    Route::get('/show/{id}', ['as' => 'inventory_item.show', 'uses' => 'InventoryItemController@show']);
    Route::get('/preview/{id}', ['as' => 'inventory_item.preview', 'uses' => 'InventoryItemController@preview']);
    Route::get('/print/{id}', ['as' => 'inventory_item.print', 'uses' => 'InventoryItemController@print']);

    Route::post('/store', ['as' => 'inventory_item.store', 'uses' => 'InventoryItemController@store']);
    Route::get('/edit/{id}', ['as' => 'inventory_item.edit', 'uses' => 'InventoryItemController@edit']);
    Route::put('/update/{id}', ['as' => 'inventory_item.update', 'uses' => 'InventoryItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'inventory_item.destroy', 'uses' => 'InventoryItemController@destroy']);
    
});
