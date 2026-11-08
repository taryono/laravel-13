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
Route::group(['prefix' => 'wallet-card', 'namespace' => 'App\Modules\WalletCard\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'wallet_card.index', 'uses' => 'WalletCardController@index']);
    Route::any('/search', ['as' => 'wallet_card.search', 'uses' => 'WalletCardController@search']);
    Route::get('/create', ['as' => 'wallet_card.create', 'uses' => 'WalletCardController@create']);
    Route::get('/show/{id}', ['as' => 'wallet_card.show', 'uses' => 'WalletCardController@show']);
    Route::get('/preview/{id}', ['as' => 'wallet_card.preview', 'uses' => 'WalletCardController@preview']);
    Route::get('/print/{id}', ['as' => 'wallet_card.print', 'uses' => 'WalletCardController@print']);

    Route::post('/store', ['as' => 'wallet_card.store', 'uses' => 'WalletCardController@store']);
    Route::get('/edit/{id}', ['as' => 'wallet_card.edit', 'uses' => 'WalletCardController@edit']);
    Route::put('/update/{id}', ['as' => 'wallet_card.update', 'uses' => 'WalletCardController@update']);
    Route::delete('/delete/{id}', ['as' => 'wallet_card.destroy', 'uses' => 'WalletCardController@destroy']);
    
});
