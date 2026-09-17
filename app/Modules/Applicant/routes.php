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
Route::group(['prefix' => 'applicant', 'namespace' => 'App\Modules\Applicant\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant.index', 'uses' => 'ApplicantController@index']);
    Route::any('/search', ['as' => 'applicant.search', 'uses' => 'ApplicantController@search']);
    Route::get('/create', ['as' => 'applicant.create', 'uses' => 'ApplicantController@create']);
    Route::get('/show/{id}', ['as' => 'applicant.show', 'uses' => 'ApplicantController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant.preview', 'uses' => 'ApplicantController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant.print', 'uses' => 'ApplicantController@print']);

    Route::post('/store', ['as' => 'applicant.store', 'uses' => 'ApplicantController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant.edit', 'uses' => 'ApplicantController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant.update', 'uses' => 'ApplicantController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant.destroy', 'uses' => 'ApplicantController@destroy']);
    
});
