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
Route::group(['prefix' => 'assessment', 'namespace' => 'App\Modules\Assessment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'assessment.index', 'uses' => 'AssessmentController@index']);
    Route::any('/search', ['as' => 'assessment.search', 'uses' => 'AssessmentController@search']);
    Route::get('/create', ['as' => 'assessment.create', 'uses' => 'AssessmentController@create']);
    Route::get('/show/{id}', ['as' => 'assessment.show', 'uses' => 'AssessmentController@show']);
    Route::get('/preview/{id}', ['as' => 'assessment.preview', 'uses' => 'AssessmentController@preview']);
    Route::get('/print/{id}', ['as' => 'assessment.print', 'uses' => 'AssessmentController@print']);

    Route::post('/store', ['as' => 'assessment.store', 'uses' => 'AssessmentController@store']);
    Route::get('/edit/{id}', ['as' => 'assessment.edit', 'uses' => 'AssessmentController@edit']);
    Route::put('/update/{id}', ['as' => 'assessment.update', 'uses' => 'AssessmentController@update']);
    Route::delete('/delete/{id}', ['as' => 'assessment.destroy', 'uses' => 'AssessmentController@destroy']);
    
});
