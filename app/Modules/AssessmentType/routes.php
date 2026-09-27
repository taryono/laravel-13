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
Route::group(['prefix' => 'assessment-type', 'namespace' => 'App\Modules\AssessmentType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'assessment_type.index', 'uses' => 'AssessmentTypeController@index']);
    Route::any('/search', ['as' => 'assessment_type.search', 'uses' => 'AssessmentTypeController@search']);
    Route::get('/create', ['as' => 'assessment_type.create', 'uses' => 'AssessmentTypeController@create']);
    Route::get('/show/{id}', ['as' => 'assessment_type.show', 'uses' => 'AssessmentTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'assessment_type.preview', 'uses' => 'AssessmentTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'assessment_type.print', 'uses' => 'AssessmentTypeController@print']);

    Route::post('/store', ['as' => 'assessment_type.store', 'uses' => 'AssessmentTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'assessment_type.edit', 'uses' => 'AssessmentTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'assessment_type.update', 'uses' => 'AssessmentTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'assessment_type.destroy', 'uses' => 'AssessmentTypeController@destroy']);
    
});
