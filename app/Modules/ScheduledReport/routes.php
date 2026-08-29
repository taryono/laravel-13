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
Route::group(['prefix' => 'scheduled-report', 'namespace' => 'App\Modules\ScheduledReport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scheduled_report.index', 'uses' => 'ScheduledReportController@index']);
    Route::any('/search', ['as' => 'scheduled_report.search', 'uses' => 'ScheduledReportController@search']);
    Route::get('/create', ['as' => 'scheduled_report.create', 'uses' => 'ScheduledReportController@create']);
    Route::get('/show/{id}', ['as' => 'scheduled_report.show', 'uses' => 'ScheduledReportController@show']);
    Route::get('/preview/{id}', ['as' => 'scheduled_report.preview', 'uses' => 'ScheduledReportController@preview']);
    Route::get('/print/{id}', ['as' => 'scheduled_report.print', 'uses' => 'ScheduledReportController@print']);

    Route::post('/store', ['as' => 'scheduled_report.store', 'uses' => 'ScheduledReportController@store']);
    Route::get('/edit/{id}', ['as' => 'scheduled_report.edit', 'uses' => 'ScheduledReportController@edit']);
    Route::put('/update/{id}', ['as' => 'scheduled_report.update', 'uses' => 'ScheduledReportController@update']);
    Route::delete('/delete/{id}', ['as' => 'scheduled_report.destroy', 'uses' => 'ScheduledReportController@destroy']);
    
});
