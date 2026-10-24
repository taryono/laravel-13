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
Route::group(['prefix' => 'purchase-request', 'namespace' => 'App\Modules\PurchaseRequest\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'purchase_request.index', 'uses' => 'PurchaseRequestController@index']);
    Route::any('/search', ['as' => 'purchase_request.search', 'uses' => 'PurchaseRequestController@search']);
    Route::get('/create', ['as' => 'purchase_request.create', 'uses' => 'PurchaseRequestController@create']);
    Route::get('/show/{id}', ['as' => 'purchase_request.show', 'uses' => 'PurchaseRequestController@show']);
    Route::get('/preview/{id}', ['as' => 'purchase_request.preview', 'uses' => 'PurchaseRequestController@preview']);
    Route::get('/print/{id}', ['as' => 'purchase_request.print', 'uses' => 'PurchaseRequestController@print']);

    Route::post('/store', ['as' => 'purchase_request.store', 'uses' => 'PurchaseRequestController@store']);
    Route::get('/edit/{id}', ['as' => 'purchase_request.edit', 'uses' => 'PurchaseRequestController@edit']);
    Route::put('/update/{id}', ['as' => 'purchase_request.update', 'uses' => 'PurchaseRequestController@update']);
    Route::delete('/delete/{id}', ['as' => 'purchase_request.destroy', 'uses' => 'PurchaseRequestController@destroy']);
    
});
