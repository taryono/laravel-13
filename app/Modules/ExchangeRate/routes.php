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
Route::group(['prefix' => 'exchange-rate', 'namespace' => 'App\Modules\ExchangeRate\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'exchange_rate.index', 'uses' => 'ExchangeRateController@index']);
    Route::any('/search', ['as' => 'exchange_rate.search', 'uses' => 'ExchangeRateController@search']);
    Route::get('/create', ['as' => 'exchange_rate.create', 'uses' => 'ExchangeRateController@create']);
    Route::get('/show/{id}', ['as' => 'exchange_rate.show', 'uses' => 'ExchangeRateController@show']);
    Route::get('/preview/{id}', ['as' => 'exchange_rate.preview', 'uses' => 'ExchangeRateController@preview']);
    Route::get('/print/{id}', ['as' => 'exchange_rate.print', 'uses' => 'ExchangeRateController@print']);

    Route::post('/store', ['as' => 'exchange_rate.store', 'uses' => 'ExchangeRateController@store']);
    Route::get('/edit/{id}', ['as' => 'exchange_rate.edit', 'uses' => 'ExchangeRateController@edit']);
    Route::put('/update/{id}', ['as' => 'exchange_rate.update', 'uses' => 'ExchangeRateController@update']);
    Route::delete('/delete/{id}', ['as' => 'exchange_rate.destroy', 'uses' => 'ExchangeRateController@destroy']);
    
});
