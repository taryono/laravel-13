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
Route::group(['prefix' => 'fiscal-year', 'namespace' => 'App\Modules\FiscalYear\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'fiscal_year.index', 'uses' => 'FiscalYearController@index']);
    Route::any('/search', ['as' => 'fiscal_year.search', 'uses' => 'FiscalYearController@search']);
    Route::get('/create', ['as' => 'fiscal_year.create', 'uses' => 'FiscalYearController@create']);
    Route::get('/show/{id}', ['as' => 'fiscal_year.show', 'uses' => 'FiscalYearController@show']);
    Route::get('/preview/{id}', ['as' => 'fiscal_year.preview', 'uses' => 'FiscalYearController@preview']);
    Route::get('/print/{id}', ['as' => 'fiscal_year.print', 'uses' => 'FiscalYearController@print']);

    Route::post('/store', ['as' => 'fiscal_year.store', 'uses' => 'FiscalYearController@store']);
    Route::get('/edit/{id}', ['as' => 'fiscal_year.edit', 'uses' => 'FiscalYearController@edit']);
    Route::put('/update/{id}', ['as' => 'fiscal_year.update', 'uses' => 'FiscalYearController@update']);
    Route::delete('/delete/{id}', ['as' => 'fiscal_year.destroy', 'uses' => 'FiscalYearController@destroy']);
    
});
