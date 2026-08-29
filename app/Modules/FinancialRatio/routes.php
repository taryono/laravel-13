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
Route::group(['prefix' => 'financial-ratio', 'namespace' => 'App\Modules\FinancialRatio\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'financial_ratio.index', 'uses' => 'FinancialRatioController@index']);
    Route::any('/search', ['as' => 'financial_ratio.search', 'uses' => 'FinancialRatioController@search']);
    Route::get('/create', ['as' => 'financial_ratio.create', 'uses' => 'FinancialRatioController@create']);
    Route::get('/show/{id}', ['as' => 'financial_ratio.show', 'uses' => 'FinancialRatioController@show']);
    Route::get('/preview/{id}', ['as' => 'financial_ratio.preview', 'uses' => 'FinancialRatioController@preview']);
    Route::get('/print/{id}', ['as' => 'financial_ratio.print', 'uses' => 'FinancialRatioController@print']);

    Route::post('/store', ['as' => 'financial_ratio.store', 'uses' => 'FinancialRatioController@store']);
    Route::get('/edit/{id}', ['as' => 'financial_ratio.edit', 'uses' => 'FinancialRatioController@edit']);
    Route::put('/update/{id}', ['as' => 'financial_ratio.update', 'uses' => 'FinancialRatioController@update']);
    Route::delete('/delete/{id}', ['as' => 'financial_ratio.destroy', 'uses' => 'FinancialRatioController@destroy']);
    
});
