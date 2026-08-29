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
Route::group(['prefix' => 'payment-request', 'namespace' => 'App\Modules\PaymentRequest\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payment_request.index', 'uses' => 'PaymentRequestController@index']);
    Route::any('/search', ['as' => 'payment_request.search', 'uses' => 'PaymentRequestController@search']);
    Route::get('/create', ['as' => 'payment_request.create', 'uses' => 'PaymentRequestController@create']);
    Route::get('/show/{id}', ['as' => 'payment_request.show', 'uses' => 'PaymentRequestController@show']);
    Route::get('/preview/{id}', ['as' => 'payment_request.preview', 'uses' => 'PaymentRequestController@preview']);
    Route::get('/print/{id}', ['as' => 'payment_request.print', 'uses' => 'PaymentRequestController@print']);

    Route::post('/store', ['as' => 'payment_request.store', 'uses' => 'PaymentRequestController@store']);
    Route::get('/edit/{id}', ['as' => 'payment_request.edit', 'uses' => 'PaymentRequestController@edit']);
    Route::put('/update/{id}', ['as' => 'payment_request.update', 'uses' => 'PaymentRequestController@update']);
    Route::delete('/delete/{id}', ['as' => 'payment_request.destroy', 'uses' => 'PaymentRequestController@destroy']);
    
});
