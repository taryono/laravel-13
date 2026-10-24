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
Route::group(['prefix' => 'applicant-status', 'namespace' => 'App\Modules\ApplicantStatus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_status.index', 'uses' => 'ApplicantStatusController@index']);
    Route::any('/search', ['as' => 'applicant_status.search', 'uses' => 'ApplicantStatusController@search']);
    Route::get('/create', ['as' => 'applicant_status.create', 'uses' => 'ApplicantStatusController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_status.show', 'uses' => 'ApplicantStatusController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_status.preview', 'uses' => 'ApplicantStatusController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_status.print', 'uses' => 'ApplicantStatusController@print']);

    Route::post('/store', ['as' => 'applicant_status.store', 'uses' => 'ApplicantStatusController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_status.edit', 'uses' => 'ApplicantStatusController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_status.update', 'uses' => 'ApplicantStatusController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_status.destroy', 'uses' => 'ApplicantStatusController@destroy']);
    
});
