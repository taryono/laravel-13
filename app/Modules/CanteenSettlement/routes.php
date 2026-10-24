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
Route::group(['prefix' => 'canteen-settlement', 'namespace' => 'App\Modules\CanteenSettlement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'canteen_settlement.index', 'uses' => 'CanteenSettlementController@index']);
    Route::any('/search', ['as' => 'canteen_settlement.search', 'uses' => 'CanteenSettlementController@search']);
    Route::get('/create', ['as' => 'canteen_settlement.create', 'uses' => 'CanteenSettlementController@create']);
    Route::get('/show/{id}', ['as' => 'canteen_settlement.show', 'uses' => 'CanteenSettlementController@show']);
    Route::get('/preview/{id}', ['as' => 'canteen_settlement.preview', 'uses' => 'CanteenSettlementController@preview']);
    Route::get('/print/{id}', ['as' => 'canteen_settlement.print', 'uses' => 'CanteenSettlementController@print']);

    Route::post('/store', ['as' => 'canteen_settlement.store', 'uses' => 'CanteenSettlementController@store']);
    Route::get('/edit/{id}', ['as' => 'canteen_settlement.edit', 'uses' => 'CanteenSettlementController@edit']);
    Route::put('/update/{id}', ['as' => 'canteen_settlement.update', 'uses' => 'CanteenSettlementController@update']);
    Route::delete('/delete/{id}', ['as' => 'canteen_settlement.destroy', 'uses' => 'CanteenSettlementController@destroy']);
    
});
