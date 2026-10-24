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
Route::group(['prefix' => 'applicant-registration', 'namespace' => 'App\Modules\ApplicantRegistration\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_registration.index', 'uses' => 'ApplicantRegistrationController@index']);
    Route::any('/search', ['as' => 'applicant_registration.search', 'uses' => 'ApplicantRegistrationController@search']);
    Route::get('/create', ['as' => 'applicant_registration.create', 'uses' => 'ApplicantRegistrationController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_registration.show', 'uses' => 'ApplicantRegistrationController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_registration.preview', 'uses' => 'ApplicantRegistrationController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_registration.print', 'uses' => 'ApplicantRegistrationController@print']);

    Route::post('/store', ['as' => 'applicant_registration.store', 'uses' => 'ApplicantRegistrationController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_registration.edit', 'uses' => 'ApplicantRegistrationController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_registration.update', 'uses' => 'ApplicantRegistrationController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_registration.destroy', 'uses' => 'ApplicantRegistrationController@destroy']);
    
});
