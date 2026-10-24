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
Route::group(['prefix' => 'digital-wallet', 'namespace' => 'App\Modules\DigitalWallet\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'digital_wallet.index', 'uses' => 'DigitalWalletController@index']);
    Route::any('/search', ['as' => 'digital_wallet.search', 'uses' => 'DigitalWalletController@search']);
    Route::get('/create', ['as' => 'digital_wallet.create', 'uses' => 'DigitalWalletController@create']);
    Route::get('/show/{id}', ['as' => 'digital_wallet.show', 'uses' => 'DigitalWalletController@show']);
    Route::get('/preview/{id}', ['as' => 'digital_wallet.preview', 'uses' => 'DigitalWalletController@preview']);
    Route::get('/print/{id}', ['as' => 'digital_wallet.print', 'uses' => 'DigitalWalletController@print']);

    Route::post('/store', ['as' => 'digital_wallet.store', 'uses' => 'DigitalWalletController@store']);
    Route::get('/edit/{id}', ['as' => 'digital_wallet.edit', 'uses' => 'DigitalWalletController@edit']);
    Route::put('/update/{id}', ['as' => 'digital_wallet.update', 'uses' => 'DigitalWalletController@update']);
    Route::delete('/delete/{id}', ['as' => 'digital_wallet.destroy', 'uses' => 'DigitalWalletController@destroy']);
    
});
