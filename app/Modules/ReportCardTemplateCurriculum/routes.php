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
Route::group(['prefix' => 'report-card-template-curriculum', 'namespace' => 'App\Modules\ReportCardTemplateCurriculum\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_template_curriculum.index', 'uses' => 'ReportCardTemplateCurriculumController@index']);
    Route::any('/search', ['as' => 'report_card_template_curriculum.search', 'uses' => 'ReportCardTemplateCurriculumController@search']);
    Route::get('/create', ['as' => 'report_card_template_curriculum.create', 'uses' => 'ReportCardTemplateCurriculumController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_template_curriculum.show', 'uses' => 'ReportCardTemplateCurriculumController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_template_curriculum.preview', 'uses' => 'ReportCardTemplateCurriculumController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_template_curriculum.print', 'uses' => 'ReportCardTemplateCurriculumController@print']);

    Route::post('/store', ['as' => 'report_card_template_curriculum.store', 'uses' => 'ReportCardTemplateCurriculumController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_template_curriculum.edit', 'uses' => 'ReportCardTemplateCurriculumController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_template_curriculum.update', 'uses' => 'ReportCardTemplateCurriculumController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_template_curriculum.destroy', 'uses' => 'ReportCardTemplateCurriculumController@destroy']);
    
});
