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
Route::group(['prefix' => 'report-card-grade', 'namespace' => 'App\Modules\ReportCardGrade\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_grade.index', 'uses' => 'ReportCardGradeController@index']);
    Route::any('/search', ['as' => 'report_card_grade.search', 'uses' => 'ReportCardGradeController@search']);
    Route::get('/create', ['as' => 'report_card_grade.create', 'uses' => 'ReportCardGradeController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_grade.show', 'uses' => 'ReportCardGradeController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_grade.preview', 'uses' => 'ReportCardGradeController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_grade.print', 'uses' => 'ReportCardGradeController@print']);

    Route::post('/store', ['as' => 'report_card_grade.store', 'uses' => 'ReportCardGradeController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_grade.edit', 'uses' => 'ReportCardGradeController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_grade.update', 'uses' => 'ReportCardGradeController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_grade.destroy', 'uses' => 'ReportCardGradeController@destroy']);
    
});
