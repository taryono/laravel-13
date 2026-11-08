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
Route::group(['prefix' => 'wallet-topup', 'namespace' => 'App\Modules\WalletTopup\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'wallet_topup.index', 'uses' => 'WalletTopupController@index']);
    Route::any('/search', ['as' => 'wallet_topup.search', 'uses' => 'WalletTopupController@search']);
    Route::get('/create', ['as' => 'wallet_topup.create', 'uses' => 'WalletTopupController@create']);
    Route::get('/show/{id}', ['as' => 'wallet_topup.show', 'uses' => 'WalletTopupController@show']);
    Route::get('/preview/{id}', ['as' => 'wallet_topup.preview', 'uses' => 'WalletTopupController@preview']);
    Route::get('/print/{id}', ['as' => 'wallet_topup.print', 'uses' => 'WalletTopupController@print']);

    Route::post('/store', ['as' => 'wallet_topup.store', 'uses' => 'WalletTopupController@store']);
    Route::get('/edit/{id}', ['as' => 'wallet_topup.edit', 'uses' => 'WalletTopupController@edit']);
    Route::put('/update/{id}', ['as' => 'wallet_topup.update', 'uses' => 'WalletTopupController@update']);
    Route::delete('/delete/{id}', ['as' => 'wallet_topup.destroy', 'uses' => 'WalletTopupController@destroy']);
    
});
