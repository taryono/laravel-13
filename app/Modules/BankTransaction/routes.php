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
Route::group(['prefix' => 'bank-transaction', 'namespace' => 'App\Modules\BankTransaction\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bank_transaction.index', 'uses' => 'BankTransactionController@index']);
    Route::any('/search', ['as' => 'bank_transaction.search', 'uses' => 'BankTransactionController@search']);
    Route::get('/create', ['as' => 'bank_transaction.create', 'uses' => 'BankTransactionController@create']);
    Route::get('/show/{id}', ['as' => 'bank_transaction.show', 'uses' => 'BankTransactionController@show']);
    Route::get('/preview/{id}', ['as' => 'bank_transaction.preview', 'uses' => 'BankTransactionController@preview']);
    Route::get('/print/{id}', ['as' => 'bank_transaction.print', 'uses' => 'BankTransactionController@print']);

    Route::post('/store', ['as' => 'bank_transaction.store', 'uses' => 'BankTransactionController@store']);
    Route::get('/edit/{id}', ['as' => 'bank_transaction.edit', 'uses' => 'BankTransactionController@edit']);
    Route::put('/update/{id}', ['as' => 'bank_transaction.update', 'uses' => 'BankTransactionController@update']);
    Route::delete('/delete/{id}', ['as' => 'bank_transaction.destroy', 'uses' => 'BankTransactionController@destroy']);
    
});
