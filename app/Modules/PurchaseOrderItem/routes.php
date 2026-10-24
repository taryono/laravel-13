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
Route::group(['prefix' => 'purchase-order-item', 'namespace' => 'App\Modules\PurchaseOrderItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'purchase_order_item.index', 'uses' => 'PurchaseOrderItemController@index']);
    Route::any('/search', ['as' => 'purchase_order_item.search', 'uses' => 'PurchaseOrderItemController@search']);
    Route::get('/create', ['as' => 'purchase_order_item.create', 'uses' => 'PurchaseOrderItemController@create']);
    Route::get('/show/{id}', ['as' => 'purchase_order_item.show', 'uses' => 'PurchaseOrderItemController@show']);
    Route::get('/preview/{id}', ['as' => 'purchase_order_item.preview', 'uses' => 'PurchaseOrderItemController@preview']);
    Route::get('/print/{id}', ['as' => 'purchase_order_item.print', 'uses' => 'PurchaseOrderItemController@print']);

    Route::post('/store', ['as' => 'purchase_order_item.store', 'uses' => 'PurchaseOrderItemController@store']);
    Route::get('/edit/{id}', ['as' => 'purchase_order_item.edit', 'uses' => 'PurchaseOrderItemController@edit']);
    Route::put('/update/{id}', ['as' => 'purchase_order_item.update', 'uses' => 'PurchaseOrderItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'purchase_order_item.destroy', 'uses' => 'PurchaseOrderItemController@destroy']);
    
});
