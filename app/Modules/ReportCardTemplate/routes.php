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
Route::group(['prefix' => 'report-card-template', 'namespace' => 'App\Modules\ReportCardTemplate\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_template.index', 'uses' => 'ReportCardTemplateController@index']);
    Route::any('/search', ['as' => 'report_card_template.search', 'uses' => 'ReportCardTemplateController@search']);
    Route::get('/create', ['as' => 'report_card_template.create', 'uses' => 'ReportCardTemplateController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_template.show', 'uses' => 'ReportCardTemplateController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_template.preview', 'uses' => 'ReportCardTemplateController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_template.print', 'uses' => 'ReportCardTemplateController@print']);

    Route::post('/store', ['as' => 'report_card_template.store', 'uses' => 'ReportCardTemplateController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_template.edit', 'uses' => 'ReportCardTemplateController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_template.update', 'uses' => 'ReportCardTemplateController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_template.destroy', 'uses' => 'ReportCardTemplateController@destroy']);
    
});
