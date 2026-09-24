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
Route::group(['prefix' => 'grade-weight-config', 'namespace' => 'App\Modules\GradeWeightConfig\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'grade_weight_config.index', 'uses' => 'GradeWeightConfigController@index']);
    Route::any('/search', ['as' => 'grade_weight_config.search', 'uses' => 'GradeWeightConfigController@search']);
    Route::get('/create', ['as' => 'grade_weight_config.create', 'uses' => 'GradeWeightConfigController@create']);
    Route::get('/show/{id}', ['as' => 'grade_weight_config.show', 'uses' => 'GradeWeightConfigController@show']);
    Route::get('/preview/{id}', ['as' => 'grade_weight_config.preview', 'uses' => 'GradeWeightConfigController@preview']);
    Route::get('/print/{id}', ['as' => 'grade_weight_config.print', 'uses' => 'GradeWeightConfigController@print']);

    Route::post('/store', ['as' => 'grade_weight_config.store', 'uses' => 'GradeWeightConfigController@store']);
    Route::get('/edit/{id}', ['as' => 'grade_weight_config.edit', 'uses' => 'GradeWeightConfigController@edit']);
    Route::put('/update/{id}', ['as' => 'grade_weight_config.update', 'uses' => 'GradeWeightConfigController@update']);
    Route::delete('/delete/{id}', ['as' => 'grade_weight_config.destroy', 'uses' => 'GradeWeightConfigController@destroy']);
    
});
