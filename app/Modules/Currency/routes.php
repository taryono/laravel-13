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
Route::group(['prefix' => 'currency', 'namespace' => 'App\Modules\Currency\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'currency.index', 'uses' => 'CurrencyController@index']);
    Route::any('/search', ['as' => 'currency.search', 'uses' => 'CurrencyController@search']);
    Route::get('/create', ['as' => 'currency.create', 'uses' => 'CurrencyController@create']);
    Route::get('/show/{id}', ['as' => 'currency.show', 'uses' => 'CurrencyController@show']);
    Route::get('/preview/{id}', ['as' => 'currency.preview', 'uses' => 'CurrencyController@preview']);
    Route::get('/print/{id}', ['as' => 'currency.print', 'uses' => 'CurrencyController@print']);

    Route::post('/store', ['as' => 'currency.store', 'uses' => 'CurrencyController@store']);
    Route::get('/edit/{id}', ['as' => 'currency.edit', 'uses' => 'CurrencyController@edit']);
    Route::put('/update/{id}', ['as' => 'currency.update', 'uses' => 'CurrencyController@update']);
    Route::delete('/delete/{id}', ['as' => 'currency.destroy', 'uses' => 'CurrencyController@destroy']);
    
});
