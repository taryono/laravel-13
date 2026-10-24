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
Route::group(['prefix' => 'fiscal-period', 'namespace' => 'App\Modules\FiscalPeriod\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'fiscal_period.index', 'uses' => 'FiscalPeriodController@index']);
    Route::any('/search', ['as' => 'fiscal_period.search', 'uses' => 'FiscalPeriodController@search']);
    Route::get('/create', ['as' => 'fiscal_period.create', 'uses' => 'FiscalPeriodController@create']);
    Route::get('/show/{id}', ['as' => 'fiscal_period.show', 'uses' => 'FiscalPeriodController@show']);
    Route::get('/preview/{id}', ['as' => 'fiscal_period.preview', 'uses' => 'FiscalPeriodController@preview']);
    Route::get('/print/{id}', ['as' => 'fiscal_period.print', 'uses' => 'FiscalPeriodController@print']);

    Route::post('/store', ['as' => 'fiscal_period.store', 'uses' => 'FiscalPeriodController@store']);
    Route::get('/edit/{id}', ['as' => 'fiscal_period.edit', 'uses' => 'FiscalPeriodController@edit']);
    Route::put('/update/{id}', ['as' => 'fiscal_period.update', 'uses' => 'FiscalPeriodController@update']);
    Route::delete('/delete/{id}', ['as' => 'fiscal_period.destroy', 'uses' => 'FiscalPeriodController@destroy']);
    
});
