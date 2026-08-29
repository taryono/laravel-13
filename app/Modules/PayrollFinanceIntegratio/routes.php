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
Route::group(['prefix' => 'payroll-finance-integration', 'namespace' => 'App\Modules\PayrollFinanceintegration\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_finance_integration.index', 'uses' => 'PayrollFinanceintegrationController@index']);
    Route::any('/search', ['as' => 'payroll_finance_integration.search', 'uses' => 'PayrollFinanceintegrationController@search']);
    Route::get('/create', ['as' => 'payroll_finance_integration.create', 'uses' => 'PayrollFinanceintegrationController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_finance_integration.show', 'uses' => 'PayrollFinanceintegrationController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_finance_integration.preview', 'uses' => 'PayrollFinanceintegrationController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_finance_integration.print', 'uses' => 'PayrollFinanceintegrationController@print']);

    Route::post('/store', ['as' => 'payroll_finance_integration.store', 'uses' => 'PayrollFinanceintegrationController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_finance_integration.edit', 'uses' => 'PayrollFinanceintegrationController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_finance_integration.update', 'uses' => 'PayrollFinanceintegrationController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_finance_integration.destroy', 'uses' => 'PayrollFinanceintegrationController@destroy']);
    
});
