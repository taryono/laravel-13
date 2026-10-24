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
Route::group(['prefix' => 'rkas-report', 'namespace' => 'App\Modules\RkasReport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_report.index', 'uses' => 'RkasReportController@index']);
    Route::any('/search', ['as' => 'rkas_report.search', 'uses' => 'RkasReportController@search']);
    Route::get('/create', ['as' => 'rkas_report.create', 'uses' => 'RkasReportController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_report.show', 'uses' => 'RkasReportController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_report.preview', 'uses' => 'RkasReportController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_report.print', 'uses' => 'RkasReportController@print']);

    Route::post('/store', ['as' => 'rkas_report.store', 'uses' => 'RkasReportController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_report.edit', 'uses' => 'RkasReportController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_report.update', 'uses' => 'RkasReportController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_report.destroy', 'uses' => 'RkasReportController@destroy']);
    
});
