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
Route::group(['prefix' => 'bank-reconciliation-item', 'namespace' => 'App\Modules\BankReconciliationItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bank_reconciliation_item.index', 'uses' => 'BankReconciliationItemController@index']);
    Route::any('/search', ['as' => 'bank_reconciliation_item.search', 'uses' => 'BankReconciliationItemController@search']);
    Route::get('/create', ['as' => 'bank_reconciliation_item.create', 'uses' => 'BankReconciliationItemController@create']);
    Route::get('/show/{id}', ['as' => 'bank_reconciliation_item.show', 'uses' => 'BankReconciliationItemController@show']);
    Route::get('/preview/{id}', ['as' => 'bank_reconciliation_item.preview', 'uses' => 'BankReconciliationItemController@preview']);
    Route::get('/print/{id}', ['as' => 'bank_reconciliation_item.print', 'uses' => 'BankReconciliationItemController@print']);

    Route::post('/store', ['as' => 'bank_reconciliation_item.store', 'uses' => 'BankReconciliationItemController@store']);
    Route::get('/edit/{id}', ['as' => 'bank_reconciliation_item.edit', 'uses' => 'BankReconciliationItemController@edit']);
    Route::put('/update/{id}', ['as' => 'bank_reconciliation_item.update', 'uses' => 'BankReconciliationItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'bank_reconciliation_item.destroy', 'uses' => 'BankReconciliationItemController@destroy']);
    
});
