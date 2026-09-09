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
Route::group(['prefix' => 'academic-year', 'namespace' => 'App\Modules\AcademicYear\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'academic_year.index', 'uses' => 'AcademicYearController@index']);
    Route::any('/search', ['as' => 'academic_year.search', 'uses' => 'AcademicYearController@search']);
    Route::get('/create', ['as' => 'academic_year.create', 'uses' => 'AcademicYearController@create']);
    Route::get('/show/{id}', ['as' => 'academic_year.show', 'uses' => 'AcademicYearController@show']);
    Route::get('/preview/{id}', ['as' => 'academic_year.preview', 'uses' => 'AcademicYearController@preview']);
    Route::get('/print/{id}', ['as' => 'academic_year.print', 'uses' => 'AcademicYearController@print']);

    Route::post('/store', ['as' => 'academic_year.store', 'uses' => 'AcademicYearController@store']);
    Route::get('/edit/{id}', ['as' => 'academic_year.edit', 'uses' => 'AcademicYearController@edit']);
    Route::put('/update/{id}', ['as' => 'academic_year.update', 'uses' => 'AcademicYearController@update']);
    Route::delete('/delete/{id}', ['as' => 'academic_year.destroy', 'uses' => 'AcademicYearController@destroy']);
    
});
