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
Route::group(['prefix' => 'admission-interview', 'namespace' => 'App\Modules\AdmissionInterview\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_interview.index', 'uses' => 'AdmissionInterviewController@index']);
    Route::any('/search', ['as' => 'admission_interview.search', 'uses' => 'AdmissionInterviewController@search']);
    Route::get('/create', ['as' => 'admission_interview.create', 'uses' => 'AdmissionInterviewController@create']);
    Route::get('/show/{id}', ['as' => 'admission_interview.show', 'uses' => 'AdmissionInterviewController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_interview.preview', 'uses' => 'AdmissionInterviewController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_interview.print', 'uses' => 'AdmissionInterviewController@print']);

    Route::post('/store', ['as' => 'admission_interview.store', 'uses' => 'AdmissionInterviewController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_interview.edit', 'uses' => 'AdmissionInterviewController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_interview.update', 'uses' => 'AdmissionInterviewController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_interview.destroy', 'uses' => 'AdmissionInterviewController@destroy']);
    
});
