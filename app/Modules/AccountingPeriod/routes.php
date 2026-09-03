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
Route::group(['prefix' => 'accounting-period', 'namespace' => 'App\Modules\AccountingPeriod\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'accounting_period.index', 'uses' => 'AccountingPeriodController@index']);
    Route::any('/search', ['as' => 'accounting_period.search', 'uses' => 'AccountingPeriodController@search']);
    Route::get('/create', ['as' => 'accounting_period.create', 'uses' => 'AccountingPeriodController@create']);
    Route::get('/show/{id}', ['as' => 'accounting_period.show', 'uses' => 'AccountingPeriodController@show']);
    Route::get('/preview/{id}', ['as' => 'accounting_period.preview', 'uses' => 'AccountingPeriodController@preview']);
    Route::get('/print/{id}', ['as' => 'accounting_period.print', 'uses' => 'AccountingPeriodController@print']);

    Route::post('/store', ['as' => 'accounting_period.store', 'uses' => 'AccountingPeriodController@store']);
    Route::get('/edit/{id}', ['as' => 'accounting_period.edit', 'uses' => 'AccountingPeriodController@edit']);
    Route::put('/update/{id}', ['as' => 'accounting_period.update', 'uses' => 'AccountingPeriodController@update']);
    Route::delete('/delete/{id}', ['as' => 'accounting_period.destroy', 'uses' => 'AccountingPeriodController@destroy']);
    
});
