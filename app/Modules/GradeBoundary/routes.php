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
Route::group(['prefix' => 'grade-boundary', 'namespace' => 'App\Modules\GradeBoundary\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'grade_boundary.index', 'uses' => 'GradeBoundaryController@index']);
    Route::any('/search', ['as' => 'grade_boundary.search', 'uses' => 'GradeBoundaryController@search']);
    Route::get('/create', ['as' => 'grade_boundary.create', 'uses' => 'GradeBoundaryController@create']);
    Route::get('/show/{id}', ['as' => 'grade_boundary.show', 'uses' => 'GradeBoundaryController@show']);
    Route::get('/preview/{id}', ['as' => 'grade_boundary.preview', 'uses' => 'GradeBoundaryController@preview']);
    Route::get('/print/{id}', ['as' => 'grade_boundary.print', 'uses' => 'GradeBoundaryController@print']);

    Route::post('/store', ['as' => 'grade_boundary.store', 'uses' => 'GradeBoundaryController@store']);
    Route::get('/edit/{id}', ['as' => 'grade_boundary.edit', 'uses' => 'GradeBoundaryController@edit']);
    Route::put('/update/{id}', ['as' => 'grade_boundary.update', 'uses' => 'GradeBoundaryController@update']);
    Route::delete('/delete/{id}', ['as' => 'grade_boundary.destroy', 'uses' => 'GradeBoundaryController@destroy']);
    
});
