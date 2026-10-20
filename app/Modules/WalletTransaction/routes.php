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
Route::group(['prefix' => 'wallet-transaction', 'namespace' => 'App\Modules\WalletTransaction\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'wallet_transaction.index', 'uses' => 'WalletTransactionController@index']);
    Route::any('/search', ['as' => 'wallet_transaction.search', 'uses' => 'WalletTransactionController@search']);
    Route::get('/create', ['as' => 'wallet_transaction.create', 'uses' => 'WalletTransactionController@create']);
    Route::get('/show/{id}', ['as' => 'wallet_transaction.show', 'uses' => 'WalletTransactionController@show']);
    Route::get('/preview/{id}', ['as' => 'wallet_transaction.preview', 'uses' => 'WalletTransactionController@preview']);
    Route::get('/print/{id}', ['as' => 'wallet_transaction.print', 'uses' => 'WalletTransactionController@print']);

    Route::post('/store', ['as' => 'wallet_transaction.store', 'uses' => 'WalletTransactionController@store']);
    Route::get('/edit/{id}', ['as' => 'wallet_transaction.edit', 'uses' => 'WalletTransactionController@edit']);
    Route::put('/update/{id}', ['as' => 'wallet_transaction.update', 'uses' => 'WalletTransactionController@update']);
    Route::delete('/delete/{id}', ['as' => 'wallet_transaction.destroy', 'uses' => 'WalletTransactionController@destroy']);
    
});
