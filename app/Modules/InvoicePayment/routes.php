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
Route::group(['prefix' => 'invoice-payment', 'namespace' => 'App\Modules\InvoicePayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'invoice_payment.index', 'uses' => 'InvoicePaymentController@index']);
    Route::any('/search', ['as' => 'invoice_payment.search', 'uses' => 'InvoicePaymentController@search']);
    Route::get('/create', ['as' => 'invoice_payment.create', 'uses' => 'InvoicePaymentController@create']);
    Route::get('/show/{id}', ['as' => 'invoice_payment.show', 'uses' => 'InvoicePaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'invoice_payment.preview', 'uses' => 'InvoicePaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'invoice_payment.print', 'uses' => 'InvoicePaymentController@print']);

    Route::post('/store', ['as' => 'invoice_payment.store', 'uses' => 'InvoicePaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'invoice_payment.edit', 'uses' => 'InvoicePaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'invoice_payment.update', 'uses' => 'InvoicePaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'invoice_payment.destroy', 'uses' => 'InvoicePaymentController@destroy']);
    
});
