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
Route::group(['prefix' => 'purchase-request-item', 'namespace' => 'App\Modules\PurchaseRequestItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'purchase_request_item.index', 'uses' => 'PurchaseRequestItemController@index']);
    Route::any('/search', ['as' => 'purchase_request_item.search', 'uses' => 'PurchaseRequestItemController@search']);
    Route::get('/create', ['as' => 'purchase_request_item.create', 'uses' => 'PurchaseRequestItemController@create']);
    Route::get('/show/{id}', ['as' => 'purchase_request_item.show', 'uses' => 'PurchaseRequestItemController@show']);
    Route::get('/preview/{id}', ['as' => 'purchase_request_item.preview', 'uses' => 'PurchaseRequestItemController@preview']);
    Route::get('/print/{id}', ['as' => 'purchase_request_item.print', 'uses' => 'PurchaseRequestItemController@print']);

    Route::post('/store', ['as' => 'purchase_request_item.store', 'uses' => 'PurchaseRequestItemController@store']);
    Route::get('/edit/{id}', ['as' => 'purchase_request_item.edit', 'uses' => 'PurchaseRequestItemController@edit']);
    Route::put('/update/{id}', ['as' => 'purchase_request_item.update', 'uses' => 'PurchaseRequestItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'purchase_request_item.destroy', 'uses' => 'PurchaseRequestItemController@destroy']);
    
});
