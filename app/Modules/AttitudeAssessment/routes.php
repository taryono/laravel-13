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
Route::group(['prefix' => 'attitude-assessment', 'namespace' => 'App\Modules\AttitudeAssessment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'attitude_assessment.index', 'uses' => 'AttitudeAssessmentController@index']);
    Route::any('/search', ['as' => 'attitude_assessment.search', 'uses' => 'AttitudeAssessmentController@search']);
    Route::get('/create', ['as' => 'attitude_assessment.create', 'uses' => 'AttitudeAssessmentController@create']);
    Route::get('/show/{id}', ['as' => 'attitude_assessment.show', 'uses' => 'AttitudeAssessmentController@show']);
    Route::get('/preview/{id}', ['as' => 'attitude_assessment.preview', 'uses' => 'AttitudeAssessmentController@preview']);
    Route::get('/print/{id}', ['as' => 'attitude_assessment.print', 'uses' => 'AttitudeAssessmentController@print']);

    Route::post('/store', ['as' => 'attitude_assessment.store', 'uses' => 'AttitudeAssessmentController@store']);
    Route::get('/edit/{id}', ['as' => 'attitude_assessment.edit', 'uses' => 'AttitudeAssessmentController@edit']);
    Route::put('/update/{id}', ['as' => 'attitude_assessment.update', 'uses' => 'AttitudeAssessmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'attitude_assessment.destroy', 'uses' => 'AttitudeAssessmentController@destroy']);
    
});
