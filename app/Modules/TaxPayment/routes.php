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
Route::group(['prefix' => 'tax-payment', 'namespace' => 'App\Modules\TaxPayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tax_payment.index', 'uses' => 'TaxPaymentController@index']);
    Route::any('/search', ['as' => 'tax_payment.search', 'uses' => 'TaxPaymentController@search']);
    Route::get('/create', ['as' => 'tax_payment.create', 'uses' => 'TaxPaymentController@create']);
    Route::get('/show/{id}', ['as' => 'tax_payment.show', 'uses' => 'TaxPaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'tax_payment.preview', 'uses' => 'TaxPaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'tax_payment.print', 'uses' => 'TaxPaymentController@print']);

    Route::post('/store', ['as' => 'tax_payment.store', 'uses' => 'TaxPaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'tax_payment.edit', 'uses' => 'TaxPaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'tax_payment.update', 'uses' => 'TaxPaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'tax_payment.destroy', 'uses' => 'TaxPaymentController@destroy']);
    
});
