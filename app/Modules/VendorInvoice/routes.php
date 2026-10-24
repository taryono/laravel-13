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
Route::group(['prefix' => 'vendor-invoice', 'namespace' => 'App\Modules\VendorInvoice\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'vendor_invoice.index', 'uses' => 'VendorInvoiceController@index']);
    Route::any('/search', ['as' => 'vendor_invoice.search', 'uses' => 'VendorInvoiceController@search']);
    Route::get('/create', ['as' => 'vendor_invoice.create', 'uses' => 'VendorInvoiceController@create']);
    Route::get('/show/{id}', ['as' => 'vendor_invoice.show', 'uses' => 'VendorInvoiceController@show']);
    Route::get('/preview/{id}', ['as' => 'vendor_invoice.preview', 'uses' => 'VendorInvoiceController@preview']);
    Route::get('/print/{id}', ['as' => 'vendor_invoice.print', 'uses' => 'VendorInvoiceController@print']);

    Route::post('/store', ['as' => 'vendor_invoice.store', 'uses' => 'VendorInvoiceController@store']);
    Route::get('/edit/{id}', ['as' => 'vendor_invoice.edit', 'uses' => 'VendorInvoiceController@edit']);
    Route::put('/update/{id}', ['as' => 'vendor_invoice.update', 'uses' => 'VendorInvoiceController@update']);
    Route::delete('/delete/{id}', ['as' => 'vendor_invoice.destroy', 'uses' => 'VendorInvoiceController@destroy']);
    
});
