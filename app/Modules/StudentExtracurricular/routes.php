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
Route::group(['prefix' => 'student-extracurricular', 'namespace' => 'App\Modules\StudentExtracurricular\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_extracurricular.index', 'uses' => 'StudentExtracurricularController@index']);
    Route::any('/search', ['as' => 'student_extracurricular.search', 'uses' => 'StudentExtracurricularController@search']);
    Route::get('/create', ['as' => 'student_extracurricular.create', 'uses' => 'StudentExtracurricularController@create']);
    Route::get('/show/{id}', ['as' => 'student_extracurricular.show', 'uses' => 'StudentExtracurricularController@show']);
    Route::get('/preview/{id}', ['as' => 'student_extracurricular.preview', 'uses' => 'StudentExtracurricularController@preview']);
    Route::get('/print/{id}', ['as' => 'student_extracurricular.print', 'uses' => 'StudentExtracurricularController@print']);

    Route::post('/store', ['as' => 'student_extracurricular.store', 'uses' => 'StudentExtracurricularController@store']);
    Route::get('/edit/{id}', ['as' => 'student_extracurricular.edit', 'uses' => 'StudentExtracurricularController@edit']);
    Route::put('/update/{id}', ['as' => 'student_extracurricular.update', 'uses' => 'StudentExtracurricularController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_extracurricular.destroy', 'uses' => 'StudentExtracurricularController@destroy']);
    
});
