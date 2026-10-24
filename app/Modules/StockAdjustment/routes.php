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
Route::group(['prefix' => 'stock-adjustment', 'namespace' => 'App\Modules\StockAdjustment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'stock_adjustment.index', 'uses' => 'StockAdjustmentController@index']);
    Route::any('/search', ['as' => 'stock_adjustment.search', 'uses' => 'StockAdjustmentController@search']);
    Route::get('/create', ['as' => 'stock_adjustment.create', 'uses' => 'StockAdjustmentController@create']);
    Route::get('/show/{id}', ['as' => 'stock_adjustment.show', 'uses' => 'StockAdjustmentController@show']);
    Route::get('/preview/{id}', ['as' => 'stock_adjustment.preview', 'uses' => 'StockAdjustmentController@preview']);
    Route::get('/print/{id}', ['as' => 'stock_adjustment.print', 'uses' => 'StockAdjustmentController@print']);

    Route::post('/store', ['as' => 'stock_adjustment.store', 'uses' => 'StockAdjustmentController@store']);
    Route::get('/edit/{id}', ['as' => 'stock_adjustment.edit', 'uses' => 'StockAdjustmentController@edit']);
    Route::put('/update/{id}', ['as' => 'stock_adjustment.update', 'uses' => 'StockAdjustmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'stock_adjustment.destroy', 'uses' => 'StockAdjustmentController@destroy']);
    
});
