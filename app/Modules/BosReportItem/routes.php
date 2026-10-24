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
Route::group(['prefix' => 'bos-report-item', 'namespace' => 'App\Modules\BosReportItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_report_item.index', 'uses' => 'BosReportItemController@index']);
    Route::any('/search', ['as' => 'bos_report_item.search', 'uses' => 'BosReportItemController@search']);
    Route::get('/create', ['as' => 'bos_report_item.create', 'uses' => 'BosReportItemController@create']);
    Route::get('/show/{id}', ['as' => 'bos_report_item.show', 'uses' => 'BosReportItemController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_report_item.preview', 'uses' => 'BosReportItemController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_report_item.print', 'uses' => 'BosReportItemController@print']);

    Route::post('/store', ['as' => 'bos_report_item.store', 'uses' => 'BosReportItemController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_report_item.edit', 'uses' => 'BosReportItemController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_report_item.update', 'uses' => 'BosReportItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_report_item.destroy', 'uses' => 'BosReportItemController@destroy']);
    
});
