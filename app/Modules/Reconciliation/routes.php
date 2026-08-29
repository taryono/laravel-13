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
Route::group(['prefix' => 'reconciliation', 'namespace' => 'App\Modules\Reconciliation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'reconciliation.index', 'uses' => 'ReconciliationController@index']);
    Route::any('/search', ['as' => 'reconciliation.search', 'uses' => 'ReconciliationController@search']);
    Route::get('/create', ['as' => 'reconciliation.create', 'uses' => 'ReconciliationController@create']);
    Route::get('/show/{id}', ['as' => 'reconciliation.show', 'uses' => 'ReconciliationController@show']);
    Route::get('/preview/{id}', ['as' => 'reconciliation.preview', 'uses' => 'ReconciliationController@preview']);
    Route::get('/print/{id}', ['as' => 'reconciliation.print', 'uses' => 'ReconciliationController@print']);

    Route::post('/store', ['as' => 'reconciliation.store', 'uses' => 'ReconciliationController@store']);
    Route::get('/edit/{id}', ['as' => 'reconciliation.edit', 'uses' => 'ReconciliationController@edit']);
    Route::put('/update/{id}', ['as' => 'reconciliation.update', 'uses' => 'ReconciliationController@update']);
    Route::delete('/delete/{id}', ['as' => 'reconciliation.destroy', 'uses' => 'ReconciliationController@destroy']);
    
});
