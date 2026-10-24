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
Route::group(['prefix' => 'goods-receipt', 'namespace' => 'App\Modules\GoodsReceipt\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'goods_receipt.index', 'uses' => 'GoodsReceiptController@index']);
    Route::any('/search', ['as' => 'goods_receipt.search', 'uses' => 'GoodsReceiptController@search']);
    Route::get('/create', ['as' => 'goods_receipt.create', 'uses' => 'GoodsReceiptController@create']);
    Route::get('/show/{id}', ['as' => 'goods_receipt.show', 'uses' => 'GoodsReceiptController@show']);
    Route::get('/preview/{id}', ['as' => 'goods_receipt.preview', 'uses' => 'GoodsReceiptController@preview']);
    Route::get('/print/{id}', ['as' => 'goods_receipt.print', 'uses' => 'GoodsReceiptController@print']);

    Route::post('/store', ['as' => 'goods_receipt.store', 'uses' => 'GoodsReceiptController@store']);
    Route::get('/edit/{id}', ['as' => 'goods_receipt.edit', 'uses' => 'GoodsReceiptController@edit']);
    Route::put('/update/{id}', ['as' => 'goods_receipt.update', 'uses' => 'GoodsReceiptController@update']);
    Route::delete('/delete/{id}', ['as' => 'goods_receipt.destroy', 'uses' => 'GoodsReceiptController@destroy']);
    
});
