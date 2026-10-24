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
Route::group(['prefix' => 'vendor-invoice-line', 'namespace' => 'App\Modules\VendorInvoiceLine\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'vendor_invoice_line.index', 'uses' => 'VendorInvoiceLineController@index']);
    Route::any('/search', ['as' => 'vendor_invoice_line.search', 'uses' => 'VendorInvoiceLineController@search']);
    Route::get('/create', ['as' => 'vendor_invoice_line.create', 'uses' => 'VendorInvoiceLineController@create']);
    Route::get('/show/{id}', ['as' => 'vendor_invoice_line.show', 'uses' => 'VendorInvoiceLineController@show']);
    Route::get('/preview/{id}', ['as' => 'vendor_invoice_line.preview', 'uses' => 'VendorInvoiceLineController@preview']);
    Route::get('/print/{id}', ['as' => 'vendor_invoice_line.print', 'uses' => 'VendorInvoiceLineController@print']);

    Route::post('/store', ['as' => 'vendor_invoice_line.store', 'uses' => 'VendorInvoiceLineController@store']);
    Route::get('/edit/{id}', ['as' => 'vendor_invoice_line.edit', 'uses' => 'VendorInvoiceLineController@edit']);
    Route::put('/update/{id}', ['as' => 'vendor_invoice_line.update', 'uses' => 'VendorInvoiceLineController@update']);
    Route::delete('/delete/{id}', ['as' => 'vendor_invoice_line.destroy', 'uses' => 'VendorInvoiceLineController@destroy']);
    
});
