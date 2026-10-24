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
Route::group(['prefix' => 'generated-report', 'namespace' => 'App\Modules\GeneratedReport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'generated_report.index', 'uses' => 'GeneratedReportController@index']);
    Route::any('/search', ['as' => 'generated_report.search', 'uses' => 'GeneratedReportController@search']);
    Route::get('/create', ['as' => 'generated_report.create', 'uses' => 'GeneratedReportController@create']);
    Route::get('/show/{id}', ['as' => 'generated_report.show', 'uses' => 'GeneratedReportController@show']);
    Route::get('/preview/{id}', ['as' => 'generated_report.preview', 'uses' => 'GeneratedReportController@preview']);
    Route::get('/print/{id}', ['as' => 'generated_report.print', 'uses' => 'GeneratedReportController@print']);

    Route::post('/store', ['as' => 'generated_report.store', 'uses' => 'GeneratedReportController@store']);
    Route::get('/edit/{id}', ['as' => 'generated_report.edit', 'uses' => 'GeneratedReportController@edit']);
    Route::put('/update/{id}', ['as' => 'generated_report.update', 'uses' => 'GeneratedReportController@update']);
    Route::delete('/delete/{id}', ['as' => 'generated_report.destroy', 'uses' => 'GeneratedReportController@destroy']);
    
});
