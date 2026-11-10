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
Route::group(['prefix' => 'report-template-curriculum', 'namespace' => 'App\Modules\ReportTemplateCurriculum\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_template_curriculum.index', 'uses' => 'ReportTemplateCurriculumController@index']);
    Route::any('/search', ['as' => 'report_template_curriculum.search', 'uses' => 'ReportTemplateCurriculumController@search']);
    Route::get('/create', ['as' => 'report_template_curriculum.create', 'uses' => 'ReportTemplateCurriculumController@create']);
    Route::get('/show/{id}', ['as' => 'report_template_curriculum.show', 'uses' => 'ReportTemplateCurriculumController@show']);
    Route::get('/preview/{id}', ['as' => 'report_template_curriculum.preview', 'uses' => 'ReportTemplateCurriculumController@preview']);
    Route::get('/print/{id}', ['as' => 'report_template_curriculum.print', 'uses' => 'ReportTemplateCurriculumController@print']);

    Route::post('/store', ['as' => 'report_template_curriculum.store', 'uses' => 'ReportTemplateCurriculumController@store']);
    Route::get('/edit/{id}', ['as' => 'report_template_curriculum.edit', 'uses' => 'ReportTemplateCurriculumController@edit']);
    Route::put('/update/{id}', ['as' => 'report_template_curriculum.update', 'uses' => 'ReportTemplateCurriculumController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_template_curriculum.destroy', 'uses' => 'ReportTemplateCurriculumController@destroy']);
    
});
