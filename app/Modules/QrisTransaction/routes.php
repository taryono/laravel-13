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
Route::group(['prefix' => 'qris-transaction', 'namespace' => 'App\Modules\QrisTransaction\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'qris_transaction.index', 'uses' => 'QrisTransactionController@index']);
    Route::any('/search', ['as' => 'qris_transaction.search', 'uses' => 'QrisTransactionController@search']);
    Route::get('/create', ['as' => 'qris_transaction.create', 'uses' => 'QrisTransactionController@create']);
    Route::get('/show/{id}', ['as' => 'qris_transaction.show', 'uses' => 'QrisTransactionController@show']);
    Route::get('/preview/{id}', ['as' => 'qris_transaction.preview', 'uses' => 'QrisTransactionController@preview']);
    Route::get('/print/{id}', ['as' => 'qris_transaction.print', 'uses' => 'QrisTransactionController@print']);

    Route::post('/store', ['as' => 'qris_transaction.store', 'uses' => 'QrisTransactionController@store']);
    Route::get('/edit/{id}', ['as' => 'qris_transaction.edit', 'uses' => 'QrisTransactionController@edit']);
    Route::put('/update/{id}', ['as' => 'qris_transaction.update', 'uses' => 'QrisTransactionController@update']);
    Route::delete('/delete/{id}', ['as' => 'qris_transaction.destroy', 'uses' => 'QrisTransactionController@destroy']);
    
});
