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
Route::group(['prefix' => 'bos-report', 'namespace' => 'App\Modules\BosReport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_report.index', 'uses' => 'BosReportController@index']);
    Route::any('/search', ['as' => 'bos_report.search', 'uses' => 'BosReportController@search']);
    Route::get('/create', ['as' => 'bos_report.create', 'uses' => 'BosReportController@create']);
    Route::get('/show/{id}', ['as' => 'bos_report.show', 'uses' => 'BosReportController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_report.preview', 'uses' => 'BosReportController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_report.print', 'uses' => 'BosReportController@print']);

    Route::post('/store', ['as' => 'bos_report.store', 'uses' => 'BosReportController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_report.edit', 'uses' => 'BosReportController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_report.update', 'uses' => 'BosReportController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_report.destroy', 'uses' => 'BosReportController@destroy']);
    
});
