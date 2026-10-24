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
Route::group(['prefix' => 'sorogan-evaluation', 'namespace' => 'App\Modules\SoroganEvaluation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sorogan_evaluation.index', 'uses' => 'SoroganEvaluationController@index']);
    Route::any('/search', ['as' => 'sorogan_evaluation.search', 'uses' => 'SoroganEvaluationController@search']);
    Route::get('/create', ['as' => 'sorogan_evaluation.create', 'uses' => 'SoroganEvaluationController@create']);
    Route::get('/show/{id}', ['as' => 'sorogan_evaluation.show', 'uses' => 'SoroganEvaluationController@show']);
    Route::get('/preview/{id}', ['as' => 'sorogan_evaluation.preview', 'uses' => 'SoroganEvaluationController@preview']);
    Route::get('/print/{id}', ['as' => 'sorogan_evaluation.print', 'uses' => 'SoroganEvaluationController@print']);

    Route::post('/store', ['as' => 'sorogan_evaluation.store', 'uses' => 'SoroganEvaluationController@store']);
    Route::get('/edit/{id}', ['as' => 'sorogan_evaluation.edit', 'uses' => 'SoroganEvaluationController@edit']);
    Route::put('/update/{id}', ['as' => 'sorogan_evaluation.update', 'uses' => 'SoroganEvaluationController@update']);
    Route::delete('/delete/{id}', ['as' => 'sorogan_evaluation.destroy', 'uses' => 'SoroganEvaluationController@destroy']);
    
});
