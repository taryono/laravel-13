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
Route::group(['prefix' => 'canteen-order-item', 'namespace' => 'App\Modules\CanteenOrderItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'canteen_order_item.index', 'uses' => 'CanteenOrderItemController@index']);
    Route::any('/search', ['as' => 'canteen_order_item.search', 'uses' => 'CanteenOrderItemController@search']);
    Route::get('/create', ['as' => 'canteen_order_item.create', 'uses' => 'CanteenOrderItemController@create']);
    Route::get('/show/{id}', ['as' => 'canteen_order_item.show', 'uses' => 'CanteenOrderItemController@show']);
    Route::get('/preview/{id}', ['as' => 'canteen_order_item.preview', 'uses' => 'CanteenOrderItemController@preview']);
    Route::get('/print/{id}', ['as' => 'canteen_order_item.print', 'uses' => 'CanteenOrderItemController@print']);

    Route::post('/store', ['as' => 'canteen_order_item.store', 'uses' => 'CanteenOrderItemController@store']);
    Route::get('/edit/{id}', ['as' => 'canteen_order_item.edit', 'uses' => 'CanteenOrderItemController@edit']);
    Route::put('/update/{id}', ['as' => 'canteen_order_item.update', 'uses' => 'CanteenOrderItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'canteen_order_item.destroy', 'uses' => 'CanteenOrderItemController@destroy']);
    
});
