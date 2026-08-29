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
Route::group(['prefix' => 'payroll-period', 'namespace' => 'App\Modules\PayrollPeriod\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_period.index', 'uses' => 'PayrollPeriodController@index']);
    Route::any('/search', ['as' => 'payroll_period.search', 'uses' => 'PayrollPeriodController@search']);
    Route::get('/create', ['as' => 'payroll_period.create', 'uses' => 'PayrollPeriodController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_period.show', 'uses' => 'PayrollPeriodController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_period.preview', 'uses' => 'PayrollPeriodController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_period.print', 'uses' => 'PayrollPeriodController@print']);

    Route::post('/store', ['as' => 'payroll_period.store', 'uses' => 'PayrollPeriodController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_period.edit', 'uses' => 'PayrollPeriodController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_period.update', 'uses' => 'PayrollPeriodController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_period.destroy', 'uses' => 'PayrollPeriodController@destroy']);
    
});
