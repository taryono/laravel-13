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
Route::group(['prefix' => 'admission-registration-fee', 'namespace' => 'App\Modules\AdmissionRegistrationFee\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_registration_fee.index', 'uses' => 'AdmissionRegistrationFeeController@index']);
    Route::any('/search', ['as' => 'admission_registration_fee.search', 'uses' => 'AdmissionRegistrationFeeController@search']);
    Route::get('/create', ['as' => 'admission_registration_fee.create', 'uses' => 'AdmissionRegistrationFeeController@create']);
    Route::get('/show/{id}', ['as' => 'admission_registration_fee.show', 'uses' => 'AdmissionRegistrationFeeController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_registration_fee.preview', 'uses' => 'AdmissionRegistrationFeeController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_registration_fee.print', 'uses' => 'AdmissionRegistrationFeeController@print']);

    Route::post('/store', ['as' => 'admission_registration_fee.store', 'uses' => 'AdmissionRegistrationFeeController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_registration_fee.edit', 'uses' => 'AdmissionRegistrationFeeController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_registration_fee.update', 'uses' => 'AdmissionRegistrationFeeController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_registration_fee.destroy', 'uses' => 'AdmissionRegistrationFeeController@destroy']);
    
});
