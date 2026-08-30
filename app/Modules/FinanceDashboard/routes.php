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
Route::group(['prefix' => 'finance_dashboard', 'namespace' => 'App\Modules\FinanceDashboard\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'finance_dashboard.index', 'uses' => 'FinanceDashboardController@index']);
    Route::any('/search', ['as' => 'finance_dashboard.search', 'uses' => 'FinanceDashboardController@search']);
    Route::get('/create', ['as' => 'finance_dashboard.create', 'uses' => 'FinanceDashboardController@create']);
    Route::get('/show/{id}', ['as' => 'finance_dashboard.show', 'uses' => 'FinanceDashboardController@show']);
    Route::get('/preview/{id}', ['as' => 'finance_dashboard.preview', 'uses' => 'FinanceDashboardController@preview']);
    Route::get('/print/{id}', ['as' => 'finance_dashboard.print', 'uses' => 'FinanceDashboardController@print']);

    Route::post('/store', ['as' => 'finance_dashboard.store', 'uses' => 'FinanceDashboardController@store']);
    Route::get('/edit/{id}', ['as' => 'finance_dashboard.edit', 'uses' => 'FinanceDashboardController@edit']);
    Route::put('/update/{id}', ['as' => 'finance_dashboard.update', 'uses' => 'FinanceDashboardController@update']);
    Route::delete('/delete/{id}', ['as' => 'finance_dashboard.destroy', 'uses' => 'FinanceDashboardController@destroy']);
});
