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
Route::group(['prefix' => 'goods-receipt-item', 'namespace' => 'App\Modules\GoodsReceiptItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'goods_receipt_item.index', 'uses' => 'GoodsReceiptItemController@index']);
    Route::any('/search', ['as' => 'goods_receipt_item.search', 'uses' => 'GoodsReceiptItemController@search']);
    Route::get('/create', ['as' => 'goods_receipt_item.create', 'uses' => 'GoodsReceiptItemController@create']);
    Route::get('/show/{id}', ['as' => 'goods_receipt_item.show', 'uses' => 'GoodsReceiptItemController@show']);
    Route::get('/preview/{id}', ['as' => 'goods_receipt_item.preview', 'uses' => 'GoodsReceiptItemController@preview']);
    Route::get('/print/{id}', ['as' => 'goods_receipt_item.print', 'uses' => 'GoodsReceiptItemController@print']);

    Route::post('/store', ['as' => 'goods_receipt_item.store', 'uses' => 'GoodsReceiptItemController@store']);
    Route::get('/edit/{id}', ['as' => 'goods_receipt_item.edit', 'uses' => 'GoodsReceiptItemController@edit']);
    Route::put('/update/{id}', ['as' => 'goods_receipt_item.update', 'uses' => 'GoodsReceiptItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'goods_receipt_item.destroy', 'uses' => 'GoodsReceiptItemController@destroy']);
    
});
