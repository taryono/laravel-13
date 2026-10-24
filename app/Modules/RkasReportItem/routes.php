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
Route::group(['prefix' => 'rkas-report-item', 'namespace' => 'App\Modules\RkasReportItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_report_item.index', 'uses' => 'RkasReportItemController@index']);
    Route::any('/search', ['as' => 'rkas_report_item.search', 'uses' => 'RkasReportItemController@search']);
    Route::get('/create', ['as' => 'rkas_report_item.create', 'uses' => 'RkasReportItemController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_report_item.show', 'uses' => 'RkasReportItemController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_report_item.preview', 'uses' => 'RkasReportItemController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_report_item.print', 'uses' => 'RkasReportItemController@print']);

    Route::post('/store', ['as' => 'rkas_report_item.store', 'uses' => 'RkasReportItemController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_report_item.edit', 'uses' => 'RkasReportItemController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_report_item.update', 'uses' => 'RkasReportItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_report_item.destroy', 'uses' => 'RkasReportItemController@destroy']);
    
});
