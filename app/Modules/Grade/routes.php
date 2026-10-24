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
Route::group(['prefix' => 'grade', 'namespace' => 'App\Modules\Grade\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'grade.index', 'uses' => 'GradeController@index']);
    Route::any('/search', ['as' => 'grade.search', 'uses' => 'GradeController@search']);
    Route::get('/create', ['as' => 'grade.create', 'uses' => 'GradeController@create']);
    Route::get('/show/{id}', ['as' => 'grade.show', 'uses' => 'GradeController@show']);
    Route::get('/preview/{id}', ['as' => 'grade.preview', 'uses' => 'GradeController@preview']);
    Route::get('/print/{id}', ['as' => 'grade.print', 'uses' => 'GradeController@print']);

    Route::post('/store', ['as' => 'grade.store', 'uses' => 'GradeController@store']);
    Route::get('/edit/{id}', ['as' => 'grade.edit', 'uses' => 'GradeController@edit']);
    Route::put('/update/{id}', ['as' => 'grade.update', 'uses' => 'GradeController@update']);
    Route::delete('/delete/{id}', ['as' => 'grade.destroy', 'uses' => 'GradeController@destroy']);
    
});
