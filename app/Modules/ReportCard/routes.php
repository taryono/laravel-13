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

Route::group(['prefix' => 'report-card', 'namespace' => 'App\Modules\ReportCard\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card.index', 'uses' => 'ReportCardController@index']);
    Route::any('/search', ['as' => 'report_card.search', 'uses' => 'ReportCardController@search']);
    Route::get('/create', ['as' => 'report_card.create', 'uses' => 'ReportCardController@create']);
    Route::get('/show/{id}', ['as' => 'report_card.show', 'uses' => 'ReportCardController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card.preview', 'uses' => 'ReportCardController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card.print', 'uses' => 'ReportCardController@print']);

    Route::post('/store', ['as' => 'report_card.store', 'uses' => 'ReportCardController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card.edit', 'uses' => 'ReportCardController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card.update', 'uses' => 'ReportCardController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card.destroy', 'uses' => 'ReportCardController@destroy']);

    Route::post('/generate', ['as' => 'report_card.generate', 'uses' => 'ReportCardController@generate']);
    Route::post('/bulk', ['as' => 'report_card.bulk', 'uses' => 'ReportCardController@bulk']);

    Route::get('/download', ['as' => 'report_card.download', 'uses' => 'ReportCardController@download']);
});
