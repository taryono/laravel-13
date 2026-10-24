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
Route::group(['prefix' => 'bank-mutation', 'namespace' => 'App\Modules\BankMutation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bank_mutation.index', 'uses' => 'BankMutationController@index']);
    Route::any('/search', ['as' => 'bank_mutation.search', 'uses' => 'BankMutationController@search']);
    Route::get('/create', ['as' => 'bank_mutation.create', 'uses' => 'BankMutationController@create']);
    Route::get('/show/{id}', ['as' => 'bank_mutation.show', 'uses' => 'BankMutationController@show']);
    Route::get('/preview/{id}', ['as' => 'bank_mutation.preview', 'uses' => 'BankMutationController@preview']);
    Route::get('/print/{id}', ['as' => 'bank_mutation.print', 'uses' => 'BankMutationController@print']);

    Route::post('/store', ['as' => 'bank_mutation.store', 'uses' => 'BankMutationController@store']);
    Route::get('/edit/{id}', ['as' => 'bank_mutation.edit', 'uses' => 'BankMutationController@edit']);
    Route::put('/update/{id}', ['as' => 'bank_mutation.update', 'uses' => 'BankMutationController@update']);
    Route::delete('/delete/{id}', ['as' => 'bank_mutation.destroy', 'uses' => 'BankMutationController@destroy']);
    
});
