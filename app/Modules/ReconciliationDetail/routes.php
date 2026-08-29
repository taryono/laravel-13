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
Route::group(['prefix' => 'reconciliation-detail', 'namespace' => 'App\Modules\ReconciliationDetail\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'reconciliation_detail.index', 'uses' => 'ReconciliationDetailController@index']);
    Route::any('/search', ['as' => 'reconciliation_detail.search', 'uses' => 'ReconciliationDetailController@search']);
    Route::get('/create', ['as' => 'reconciliation_detail.create', 'uses' => 'ReconciliationDetailController@create']);
    Route::get('/show/{id}', ['as' => 'reconciliation_detail.show', 'uses' => 'ReconciliationDetailController@show']);
    Route::get('/preview/{id}', ['as' => 'reconciliation_detail.preview', 'uses' => 'ReconciliationDetailController@preview']);
    Route::get('/print/{id}', ['as' => 'reconciliation_detail.print', 'uses' => 'ReconciliationDetailController@print']);

    Route::post('/store', ['as' => 'reconciliation_detail.store', 'uses' => 'ReconciliationDetailController@store']);
    Route::get('/edit/{id}', ['as' => 'reconciliation_detail.edit', 'uses' => 'ReconciliationDetailController@edit']);
    Route::put('/update/{id}', ['as' => 'reconciliation_detail.update', 'uses' => 'ReconciliationDetailController@update']);
    Route::delete('/delete/{id}', ['as' => 'reconciliation_detail.destroy', 'uses' => 'ReconciliationDetailController@destroy']);
    
});
