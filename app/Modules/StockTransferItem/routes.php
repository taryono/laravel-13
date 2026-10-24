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
Route::group(['prefix' => 'stock-transfer-item', 'namespace' => 'App\Modules\StockTransferItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'stock_transfer_item.index', 'uses' => 'StockTransferItemController@index']);
    Route::any('/search', ['as' => 'stock_transfer_item.search', 'uses' => 'StockTransferItemController@search']);
    Route::get('/create', ['as' => 'stock_transfer_item.create', 'uses' => 'StockTransferItemController@create']);
    Route::get('/show/{id}', ['as' => 'stock_transfer_item.show', 'uses' => 'StockTransferItemController@show']);
    Route::get('/preview/{id}', ['as' => 'stock_transfer_item.preview', 'uses' => 'StockTransferItemController@preview']);
    Route::get('/print/{id}', ['as' => 'stock_transfer_item.print', 'uses' => 'StockTransferItemController@print']);

    Route::post('/store', ['as' => 'stock_transfer_item.store', 'uses' => 'StockTransferItemController@store']);
    Route::get('/edit/{id}', ['as' => 'stock_transfer_item.edit', 'uses' => 'StockTransferItemController@edit']);
    Route::put('/update/{id}', ['as' => 'stock_transfer_item.update', 'uses' => 'StockTransferItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'stock_transfer_item.destroy', 'uses' => 'StockTransferItemController@destroy']);
    
});
