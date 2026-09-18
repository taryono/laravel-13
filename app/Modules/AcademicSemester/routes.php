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
Route::group(['prefix' => 'academic-semester', 'namespace' => 'App\Modules\AcademicSemester\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'academic_semester.index', 'uses' => 'AcademicSemesterController@index']);
    Route::any('/search', ['as' => 'academic_semester.search', 'uses' => 'AcademicSemesterController@search']);
    Route::get('/create', ['as' => 'academic_semester.create', 'uses' => 'AcademicSemesterController@create']);
    Route::get('/show/{id}', ['as' => 'academic_semester.show', 'uses' => 'AcademicSemesterController@show']);
    Route::get('/preview/{id}', ['as' => 'academic_semester.preview', 'uses' => 'AcademicSemesterController@preview']);
    Route::get('/print/{id}', ['as' => 'academic_semester.print', 'uses' => 'AcademicSemesterController@print']);

    Route::post('/store', ['as' => 'academic_semester.store', 'uses' => 'AcademicSemesterController@store']);
    Route::get('/edit/{id}', ['as' => 'academic_semester.edit', 'uses' => 'AcademicSemesterController@edit']);
    Route::put('/update/{id}', ['as' => 'academic_semester.update', 'uses' => 'AcademicSemesterController@update']);
    Route::delete('/delete/{id}', ['as' => 'academic_semester.destroy', 'uses' => 'AcademicSemesterController@destroy']);
    
});
