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
Route::group(['prefix' => 'purchase-order', 'namespace' => 'App\Modules\PurchaseOrder\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'purchase_order.index', 'uses' => 'PurchaseOrderController@index']);
    Route::any('/search', ['as' => 'purchase_order.search', 'uses' => 'PurchaseOrderController@search']);
    Route::get('/create', ['as' => 'purchase_order.create', 'uses' => 'PurchaseOrderController@create']);
    Route::get('/show/{id}', ['as' => 'purchase_order.show', 'uses' => 'PurchaseOrderController@show']);
    Route::get('/preview/{id}', ['as' => 'purchase_order.preview', 'uses' => 'PurchaseOrderController@preview']);
    Route::get('/print/{id}', ['as' => 'purchase_order.print', 'uses' => 'PurchaseOrderController@print']);

    Route::post('/store', ['as' => 'purchase_order.store', 'uses' => 'PurchaseOrderController@store']);
    Route::get('/edit/{id}', ['as' => 'purchase_order.edit', 'uses' => 'PurchaseOrderController@edit']);
    Route::put('/update/{id}', ['as' => 'purchase_order.update', 'uses' => 'PurchaseOrderController@update']);
    Route::delete('/delete/{id}', ['as' => 'purchase_order.destroy', 'uses' => 'PurchaseOrderController@destroy']);
    
});
