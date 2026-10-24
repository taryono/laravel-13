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
Route::group(['prefix' => 'stock-transfer', 'namespace' => 'App\Modules\StockTransfer\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'stock_transfer.index', 'uses' => 'StockTransferController@index']);
    Route::any('/search', ['as' => 'stock_transfer.search', 'uses' => 'StockTransferController@search']);
    Route::get('/create', ['as' => 'stock_transfer.create', 'uses' => 'StockTransferController@create']);
    Route::get('/show/{id}', ['as' => 'stock_transfer.show', 'uses' => 'StockTransferController@show']);
    Route::get('/preview/{id}', ['as' => 'stock_transfer.preview', 'uses' => 'StockTransferController@preview']);
    Route::get('/print/{id}', ['as' => 'stock_transfer.print', 'uses' => 'StockTransferController@print']);

    Route::post('/store', ['as' => 'stock_transfer.store', 'uses' => 'StockTransferController@store']);
    Route::get('/edit/{id}', ['as' => 'stock_transfer.edit', 'uses' => 'StockTransferController@edit']);
    Route::put('/update/{id}', ['as' => 'stock_transfer.update', 'uses' => 'StockTransferController@update']);
    Route::delete('/delete/{id}', ['as' => 'stock_transfer.destroy', 'uses' => 'StockTransferController@destroy']);
    
});
