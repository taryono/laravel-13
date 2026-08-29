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
Route::group(['prefix' => 'bank-account', 'namespace' => 'App\Modules\BankAccount\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bank_account.index', 'uses' => 'BankAccountController@index']);
    Route::any('/search', ['as' => 'bank_account.search', 'uses' => 'BankAccountController@search']);
    Route::get('/create', ['as' => 'bank_account.create', 'uses' => 'BankAccountController@create']);
    Route::get('/show/{id}', ['as' => 'bank_account.show', 'uses' => 'BankAccountController@show']);
    Route::get('/preview/{id}', ['as' => 'bank_account.preview', 'uses' => 'BankAccountController@preview']);
    Route::get('/print/{id}', ['as' => 'bank_account.print', 'uses' => 'BankAccountController@print']);

    Route::post('/store', ['as' => 'bank_account.store', 'uses' => 'BankAccountController@store']);
    Route::get('/edit/{id}', ['as' => 'bank_account.edit', 'uses' => 'BankAccountController@edit']);
    Route::put('/update/{id}', ['as' => 'bank_account.update', 'uses' => 'BankAccountController@update']);
    Route::delete('/delete/{id}', ['as' => 'bank_account.destroy', 'uses' => 'BankAccountController@destroy']);
    
});
