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
Route::group(['prefix' => 'fiscal-period-closing', 'namespace' => 'App\Modules\FiscalPeriodClosing\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'fiscal_period_closing.index', 'uses' => 'FiscalPeriodClosingController@index']);
    Route::any('/search', ['as' => 'fiscal_period_closing.search', 'uses' => 'FiscalPeriodClosingController@search']);
    Route::get('/create', ['as' => 'fiscal_period_closing.create', 'uses' => 'FiscalPeriodClosingController@create']);
    Route::get('/show/{id}', ['as' => 'fiscal_period_closing.show', 'uses' => 'FiscalPeriodClosingController@show']);
    Route::get('/preview/{id}', ['as' => 'fiscal_period_closing.preview', 'uses' => 'FiscalPeriodClosingController@preview']);
    Route::get('/print/{id}', ['as' => 'fiscal_period_closing.print', 'uses' => 'FiscalPeriodClosingController@print']);

    Route::post('/store', ['as' => 'fiscal_period_closing.store', 'uses' => 'FiscalPeriodClosingController@store']);
    Route::get('/edit/{id}', ['as' => 'fiscal_period_closing.edit', 'uses' => 'FiscalPeriodClosingController@edit']);
    Route::put('/update/{id}', ['as' => 'fiscal_period_closing.update', 'uses' => 'FiscalPeriodClosingController@update']);
    Route::delete('/delete/{id}', ['as' => 'fiscal_period_closing.destroy', 'uses' => 'FiscalPeriodClosingController@destroy']);
    
});
