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
Route::group(['prefix' => 'payment-transaction', 'namespace' => 'App\Modules\PaymentTransaction\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payment_transaction.index', 'uses' => 'PaymentTransactionController@index']);
    Route::any('/search', ['as' => 'payment_transaction.search', 'uses' => 'PaymentTransactionController@search']);
    Route::get('/create', ['as' => 'payment_transaction.create', 'uses' => 'PaymentTransactionController@create']);
    Route::get('/show/{id}', ['as' => 'payment_transaction.show', 'uses' => 'PaymentTransactionController@show']);
    Route::get('/preview/{id}', ['as' => 'payment_transaction.preview', 'uses' => 'PaymentTransactionController@preview']);
    Route::get('/print/{id}', ['as' => 'payment_transaction.print', 'uses' => 'PaymentTransactionController@print']);

    Route::post('/store', ['as' => 'payment_transaction.store', 'uses' => 'PaymentTransactionController@store']);
    Route::get('/edit/{id}', ['as' => 'payment_transaction.edit', 'uses' => 'PaymentTransactionController@edit']);
    Route::put('/update/{id}', ['as' => 'payment_transaction.update', 'uses' => 'PaymentTransactionController@update']);
    Route::delete('/delete/{id}', ['as' => 'payment_transaction.destroy', 'uses' => 'PaymentTransactionController@destroy']);
    
});
