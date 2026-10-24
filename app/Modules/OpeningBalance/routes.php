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
Route::group(['prefix' => 'opening-balance', 'namespace' => 'App\Modules\OpeningBalance\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'opening_balance.index', 'uses' => 'OpeningBalanceController@index']);
    Route::any('/search', ['as' => 'opening_balance.search', 'uses' => 'OpeningBalanceController@search']);
    Route::get('/create', ['as' => 'opening_balance.create', 'uses' => 'OpeningBalanceController@create']);
    Route::get('/show/{id}', ['as' => 'opening_balance.show', 'uses' => 'OpeningBalanceController@show']);
    Route::get('/preview/{id}', ['as' => 'opening_balance.preview', 'uses' => 'OpeningBalanceController@preview']);
    Route::get('/print/{id}', ['as' => 'opening_balance.print', 'uses' => 'OpeningBalanceController@print']);

    Route::post('/store', ['as' => 'opening_balance.store', 'uses' => 'OpeningBalanceController@store']);
    Route::get('/edit/{id}', ['as' => 'opening_balance.edit', 'uses' => 'OpeningBalanceController@edit']);
    Route::put('/update/{id}', ['as' => 'opening_balance.update', 'uses' => 'OpeningBalanceController@update']);
    Route::delete('/delete/{id}', ['as' => 'opening_balance.destroy', 'uses' => 'OpeningBalanceController@destroy']);
    
});
