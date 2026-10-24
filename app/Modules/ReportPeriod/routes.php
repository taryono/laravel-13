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
Route::group(['prefix' => 'report-period', 'namespace' => 'App\Modules\ReportPeriod\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_period.index', 'uses' => 'ReportPeriodController@index']);
    Route::any('/search', ['as' => 'report_period.search', 'uses' => 'ReportPeriodController@search']);
    Route::get('/create', ['as' => 'report_period.create', 'uses' => 'ReportPeriodController@create']);
    Route::get('/show/{id}', ['as' => 'report_period.show', 'uses' => 'ReportPeriodController@show']);
    Route::get('/preview/{id}', ['as' => 'report_period.preview', 'uses' => 'ReportPeriodController@preview']);
    Route::get('/print/{id}', ['as' => 'report_period.print', 'uses' => 'ReportPeriodController@print']);

    Route::post('/store', ['as' => 'report_period.store', 'uses' => 'ReportPeriodController@store']);
    Route::get('/edit/{id}', ['as' => 'report_period.edit', 'uses' => 'ReportPeriodController@edit']);
    Route::put('/update/{id}', ['as' => 'report_period.update', 'uses' => 'ReportPeriodController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_period.destroy', 'uses' => 'ReportPeriodController@destroy']);
    
});
