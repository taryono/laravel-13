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
Route::group(['prefix' => 'vendor-payment', 'namespace' => 'App\Modules\VendorPayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'vendor_payment.index', 'uses' => 'VendorPaymentController@index']);
    Route::any('/search', ['as' => 'vendor_payment.search', 'uses' => 'VendorPaymentController@search']);
    Route::get('/create', ['as' => 'vendor_payment.create', 'uses' => 'VendorPaymentController@create']);
    Route::get('/show/{id}', ['as' => 'vendor_payment.show', 'uses' => 'VendorPaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'vendor_payment.preview', 'uses' => 'VendorPaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'vendor_payment.print', 'uses' => 'VendorPaymentController@print']);

    Route::post('/store', ['as' => 'vendor_payment.store', 'uses' => 'VendorPaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'vendor_payment.edit', 'uses' => 'VendorPaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'vendor_payment.update', 'uses' => 'VendorPaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'vendor_payment.destroy', 'uses' => 'VendorPaymentController@destroy']);
    
});
