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
Route::group(['prefix' => 'report-template', 'namespace' => 'App\Modules\ReportTemplate\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_template.index', 'uses' => 'ReportTemplateController@index']);
    Route::any('/search', ['as' => 'report_template.search', 'uses' => 'ReportTemplateController@search']);
    Route::get('/create', ['as' => 'report_template.create', 'uses' => 'ReportTemplateController@create']);
    Route::get('/show/{id}', ['as' => 'report_template.show', 'uses' => 'ReportTemplateController@show']);
    Route::get('/preview/{id}', ['as' => 'report_template.preview', 'uses' => 'ReportTemplateController@preview']);
    Route::get('/print/{id}', ['as' => 'report_template.print', 'uses' => 'ReportTemplateController@print']);

    Route::post('/store', ['as' => 'report_template.store', 'uses' => 'ReportTemplateController@store']);
    Route::get('/edit/{id}', ['as' => 'report_template.edit', 'uses' => 'ReportTemplateController@edit']);
    Route::put('/update/{id}', ['as' => 'report_template.update', 'uses' => 'ReportTemplateController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_template.destroy', 'uses' => 'ReportTemplateController@destroy']);
    
});
