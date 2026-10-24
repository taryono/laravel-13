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
Route::group(['prefix' => 'stock-adjustment-item', 'namespace' => 'App\Modules\StockAdjustmentItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'stock_adjustment_item.index', 'uses' => 'StockAdjustmentItemController@index']);
    Route::any('/search', ['as' => 'stock_adjustment_item.search', 'uses' => 'StockAdjustmentItemController@search']);
    Route::get('/create', ['as' => 'stock_adjustment_item.create', 'uses' => 'StockAdjustmentItemController@create']);
    Route::get('/show/{id}', ['as' => 'stock_adjustment_item.show', 'uses' => 'StockAdjustmentItemController@show']);
    Route::get('/preview/{id}', ['as' => 'stock_adjustment_item.preview', 'uses' => 'StockAdjustmentItemController@preview']);
    Route::get('/print/{id}', ['as' => 'stock_adjustment_item.print', 'uses' => 'StockAdjustmentItemController@print']);

    Route::post('/store', ['as' => 'stock_adjustment_item.store', 'uses' => 'StockAdjustmentItemController@store']);
    Route::get('/edit/{id}', ['as' => 'stock_adjustment_item.edit', 'uses' => 'StockAdjustmentItemController@edit']);
    Route::put('/update/{id}', ['as' => 'stock_adjustment_item.update', 'uses' => 'StockAdjustmentItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'stock_adjustment_item.destroy', 'uses' => 'StockAdjustmentItemController@destroy']);
    
});
