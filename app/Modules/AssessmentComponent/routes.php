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
Route::group(['prefix' => 'assessment-component', 'namespace' => 'App\Modules\AssessmentComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'assessment_component.index', 'uses' => 'AssessmentComponentController@index']);
    Route::any('/search', ['as' => 'assessment_component.search', 'uses' => 'AssessmentComponentController@search']);
    Route::get('/create', ['as' => 'assessment_component.create', 'uses' => 'AssessmentComponentController@create']);
    Route::get('/show/{id}', ['as' => 'assessment_component.show', 'uses' => 'AssessmentComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'assessment_component.preview', 'uses' => 'AssessmentComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'assessment_component.print', 'uses' => 'AssessmentComponentController@print']);

    Route::post('/store', ['as' => 'assessment_component.store', 'uses' => 'AssessmentComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'assessment_component.edit', 'uses' => 'AssessmentComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'assessment_component.update', 'uses' => 'AssessmentComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'assessment_component.destroy', 'uses' => 'AssessmentComponentController@destroy']);
    
});
