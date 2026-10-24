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
Route::group(['prefix' => 'assessment-rubric', 'namespace' => 'App\Modules\AssessmentRubric\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'assessment_rubric.index', 'uses' => 'AssessmentRubricController@index']);
    Route::any('/search', ['as' => 'assessment_rubric.search', 'uses' => 'AssessmentRubricController@search']);
    Route::get('/create', ['as' => 'assessment_rubric.create', 'uses' => 'AssessmentRubricController@create']);
    Route::get('/show/{id}', ['as' => 'assessment_rubric.show', 'uses' => 'AssessmentRubricController@show']);
    Route::get('/preview/{id}', ['as' => 'assessment_rubric.preview', 'uses' => 'AssessmentRubricController@preview']);
    Route::get('/print/{id}', ['as' => 'assessment_rubric.print', 'uses' => 'AssessmentRubricController@print']);

    Route::post('/store', ['as' => 'assessment_rubric.store', 'uses' => 'AssessmentRubricController@store']);
    Route::get('/edit/{id}', ['as' => 'assessment_rubric.edit', 'uses' => 'AssessmentRubricController@edit']);
    Route::put('/update/{id}', ['as' => 'assessment_rubric.update', 'uses' => 'AssessmentRubricController@update']);
    Route::delete('/delete/{id}', ['as' => 'assessment_rubric.destroy', 'uses' => 'AssessmentRubricController@destroy']);
    
});
