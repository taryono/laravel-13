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
Route::group(['prefix' => 'payment-gateway', 'namespace' => 'App\Modules\PaymentGateway\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payment_gateway.index', 'uses' => 'PaymentGatewayController@index']);
    Route::any('/search', ['as' => 'payment_gateway.search', 'uses' => 'PaymentGatewayController@search']);
    Route::get('/create', ['as' => 'payment_gateway.create', 'uses' => 'PaymentGatewayController@create']);
    Route::get('/show/{id}', ['as' => 'payment_gateway.show', 'uses' => 'PaymentGatewayController@show']);
    Route::get('/preview/{id}', ['as' => 'payment_gateway.preview', 'uses' => 'PaymentGatewayController@preview']);
    Route::get('/print/{id}', ['as' => 'payment_gateway.print', 'uses' => 'PaymentGatewayController@print']);

    Route::post('/store', ['as' => 'payment_gateway.store', 'uses' => 'PaymentGatewayController@store']);
    Route::get('/edit/{id}', ['as' => 'payment_gateway.edit', 'uses' => 'PaymentGatewayController@edit']);
    Route::put('/update/{id}', ['as' => 'payment_gateway.update', 'uses' => 'PaymentGatewayController@update']);
    Route::delete('/delete/{id}', ['as' => 'payment_gateway.destroy', 'uses' => 'PaymentGatewayController@destroy']);
    
});
