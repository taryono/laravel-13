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
Route::group(['prefix' => 'report-card-component', 'namespace' => 'App\Modules\ReportCardComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_component.index', 'uses' => 'ReportCardComponentController@index']);
    Route::any('/search', ['as' => 'report_card_component.search', 'uses' => 'ReportCardComponentController@search']);
    Route::get('/create', ['as' => 'report_card_component.create', 'uses' => 'ReportCardComponentController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_component.show', 'uses' => 'ReportCardComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_component.preview', 'uses' => 'ReportCardComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_component.print', 'uses' => 'ReportCardComponentController@print']);

    Route::post('/store', ['as' => 'report_card_component.store', 'uses' => 'ReportCardComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_component.edit', 'uses' => 'ReportCardComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_component.update', 'uses' => 'ReportCardComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_component.destroy', 'uses' => 'ReportCardComponentController@destroy']);
    
});
