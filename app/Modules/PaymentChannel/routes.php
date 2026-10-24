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
Route::group(['prefix' => 'payment-channel', 'namespace' => 'App\Modules\PaymentChannel\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payment_channel.index', 'uses' => 'PaymentChannelController@index']);
    Route::any('/search', ['as' => 'payment_channel.search', 'uses' => 'PaymentChannelController@search']);
    Route::get('/create', ['as' => 'payment_channel.create', 'uses' => 'PaymentChannelController@create']);
    Route::get('/show/{id}', ['as' => 'payment_channel.show', 'uses' => 'PaymentChannelController@show']);
    Route::get('/preview/{id}', ['as' => 'payment_channel.preview', 'uses' => 'PaymentChannelController@preview']);
    Route::get('/print/{id}', ['as' => 'payment_channel.print', 'uses' => 'PaymentChannelController@print']);

    Route::post('/store', ['as' => 'payment_channel.store', 'uses' => 'PaymentChannelController@store']);
    Route::get('/edit/{id}', ['as' => 'payment_channel.edit', 'uses' => 'PaymentChannelController@edit']);
    Route::put('/update/{id}', ['as' => 'payment_channel.update', 'uses' => 'PaymentChannelController@update']);
    Route::delete('/delete/{id}', ['as' => 'payment_channel.destroy', 'uses' => 'PaymentChannelController@destroy']);
    
});
