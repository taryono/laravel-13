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
Route::group(['prefix' => 'bank-mutation-import', 'namespace' => 'App\Modules\BankMutationImport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bank_mutation_import.index', 'uses' => 'BankMutationImportController@index']);
    Route::any('/search', ['as' => 'bank_mutation_import.search', 'uses' => 'BankMutationImportController@search']);
    Route::get('/create', ['as' => 'bank_mutation_import.create', 'uses' => 'BankMutationImportController@create']);
    Route::get('/show/{id}', ['as' => 'bank_mutation_import.show', 'uses' => 'BankMutationImportController@show']);
    Route::get('/preview/{id}', ['as' => 'bank_mutation_import.preview', 'uses' => 'BankMutationImportController@preview']);
    Route::get('/print/{id}', ['as' => 'bank_mutation_import.print', 'uses' => 'BankMutationImportController@print']);

    Route::post('/store', ['as' => 'bank_mutation_import.store', 'uses' => 'BankMutationImportController@store']);
    Route::get('/edit/{id}', ['as' => 'bank_mutation_import.edit', 'uses' => 'BankMutationImportController@edit']);
    Route::put('/update/{id}', ['as' => 'bank_mutation_import.update', 'uses' => 'BankMutationImportController@update']);
    Route::delete('/delete/{id}', ['as' => 'bank_mutation_import.destroy', 'uses' => 'BankMutationImportController@destroy']);
    
});
