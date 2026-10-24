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
Route::group(['prefix' => 'report-card-template-section', 'namespace' => 'App\Modules\ReportCardTemplateSection\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_template_section.index', 'uses' => 'ReportCardTemplateSectionController@index']);
    Route::any('/search', ['as' => 'report_card_template_section.search', 'uses' => 'ReportCardTemplateSectionController@search']);
    Route::get('/create', ['as' => 'report_card_template_section.create', 'uses' => 'ReportCardTemplateSectionController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_template_section.show', 'uses' => 'ReportCardTemplateSectionController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_template_section.preview', 'uses' => 'ReportCardTemplateSectionController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_template_section.print', 'uses' => 'ReportCardTemplateSectionController@print']);

    Route::post('/store', ['as' => 'report_card_template_section.store', 'uses' => 'ReportCardTemplateSectionController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_template_section.edit', 'uses' => 'ReportCardTemplateSectionController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_template_section.update', 'uses' => 'ReportCardTemplateSectionController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_template_section.destroy', 'uses' => 'ReportCardTemplateSectionController@destroy']);
    
});
