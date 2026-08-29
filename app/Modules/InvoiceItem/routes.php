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
Route::group(['prefix' => 'invoice-item', 'namespace' => 'App\Modules\InvoiceItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'invoice_item.index', 'uses' => 'InvoiceItemController@index']);
    Route::any('/search', ['as' => 'invoice_item.search', 'uses' => 'InvoiceItemController@search']);
    Route::get('/create', ['as' => 'invoice_item.create', 'uses' => 'InvoiceItemController@create']);
    Route::get('/show/{id}', ['as' => 'invoice_item.show', 'uses' => 'InvoiceItemController@show']);
    Route::get('/preview/{id}', ['as' => 'invoice_item.preview', 'uses' => 'InvoiceItemController@preview']);
    Route::get('/print/{id}', ['as' => 'invoice_item.print', 'uses' => 'InvoiceItemController@print']);

    Route::post('/store', ['as' => 'invoice_item.store', 'uses' => 'InvoiceItemController@store']);
    Route::get('/edit/{id}', ['as' => 'invoice_item.edit', 'uses' => 'InvoiceItemController@edit']);
    Route::put('/update/{id}', ['as' => 'invoice_item.update', 'uses' => 'InvoiceItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'invoice_item.destroy', 'uses' => 'InvoiceItemController@destroy']);
    
});
