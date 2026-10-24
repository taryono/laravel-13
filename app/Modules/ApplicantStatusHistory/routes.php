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
Route::group(['prefix' => 'applicant-status-history', 'namespace' => 'App\Modules\ApplicantStatusHistory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_status_history.index', 'uses' => 'ApplicantStatusHistoryController@index']);
    Route::any('/search', ['as' => 'applicant_status_history.search', 'uses' => 'ApplicantStatusHistoryController@search']);
    Route::get('/create', ['as' => 'applicant_status_history.create', 'uses' => 'ApplicantStatusHistoryController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_status_history.show', 'uses' => 'ApplicantStatusHistoryController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_status_history.preview', 'uses' => 'ApplicantStatusHistoryController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_status_history.print', 'uses' => 'ApplicantStatusHistoryController@print']);

    Route::post('/store', ['as' => 'applicant_status_history.store', 'uses' => 'ApplicantStatusHistoryController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_status_history.edit', 'uses' => 'ApplicantStatusHistoryController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_status_history.update', 'uses' => 'ApplicantStatusHistoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_status_history.destroy', 'uses' => 'ApplicantStatusHistoryController@destroy']);
    
});
