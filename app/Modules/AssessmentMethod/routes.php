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
Route::group(['prefix' => 'assessment-method', 'namespace' => 'App\Modules\AssessmentMethod\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'assessment_method.index', 'uses' => 'AssessmentMethodController@index']);
    Route::any('/search', ['as' => 'assessment_method.search', 'uses' => 'AssessmentMethodController@search']);
    Route::get('/create', ['as' => 'assessment_method.create', 'uses' => 'AssessmentMethodController@create']);
    Route::get('/show/{id}', ['as' => 'assessment_method.show', 'uses' => 'AssessmentMethodController@show']);
    Route::get('/preview/{id}', ['as' => 'assessment_method.preview', 'uses' => 'AssessmentMethodController@preview']);
    Route::get('/print/{id}', ['as' => 'assessment_method.print', 'uses' => 'AssessmentMethodController@print']);

    Route::post('/store', ['as' => 'assessment_method.store', 'uses' => 'AssessmentMethodController@store']);
    Route::get('/edit/{id}', ['as' => 'assessment_method.edit', 'uses' => 'AssessmentMethodController@edit']);
    Route::put('/update/{id}', ['as' => 'assessment_method.update', 'uses' => 'AssessmentMethodController@update']);
    Route::delete('/delete/{id}', ['as' => 'assessment_method.destroy', 'uses' => 'AssessmentMethodController@destroy']);
    
});
