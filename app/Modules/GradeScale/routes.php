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
Route::group(['prefix' => 'grade-scale', 'namespace' => 'App\Modules\GradeScale\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'grade_scale.index', 'uses' => 'GradeScaleController@index']);
    Route::any('/search', ['as' => 'grade_scale.search', 'uses' => 'GradeScaleController@search']);
    Route::get('/create', ['as' => 'grade_scale.create', 'uses' => 'GradeScaleController@create']);
    Route::get('/show/{id}', ['as' => 'grade_scale.show', 'uses' => 'GradeScaleController@show']);
    Route::get('/preview/{id}', ['as' => 'grade_scale.preview', 'uses' => 'GradeScaleController@preview']);
    Route::get('/print/{id}', ['as' => 'grade_scale.print', 'uses' => 'GradeScaleController@print']);

    Route::post('/store', ['as' => 'grade_scale.store', 'uses' => 'GradeScaleController@store']);
    Route::get('/edit/{id}', ['as' => 'grade_scale.edit', 'uses' => 'GradeScaleController@edit']);
    Route::put('/update/{id}', ['as' => 'grade_scale.update', 'uses' => 'GradeScaleController@update']);
    Route::delete('/delete/{id}', ['as' => 'grade_scale.destroy', 'uses' => 'GradeScaleController@destroy']);
    
});
