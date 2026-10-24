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
Route::group(['prefix' => 'academic-registration-fee', 'namespace' => 'App\Modules\AcademicRegistrationFee\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'academic_registration_fee.index', 'uses' => 'AcademicRegistrationFeeController@index']);
    Route::any('/search', ['as' => 'academic_registration_fee.search', 'uses' => 'AcademicRegistrationFeeController@search']);
    Route::get('/create', ['as' => 'academic_registration_fee.create', 'uses' => 'AcademicRegistrationFeeController@create']);
    Route::get('/show/{id}', ['as' => 'academic_registration_fee.show', 'uses' => 'AcademicRegistrationFeeController@show']);
    Route::get('/preview/{id}', ['as' => 'academic_registration_fee.preview', 'uses' => 'AcademicRegistrationFeeController@preview']);
    Route::get('/print/{id}', ['as' => 'academic_registration_fee.print', 'uses' => 'AcademicRegistrationFeeController@print']);

    Route::post('/store', ['as' => 'academic_registration_fee.store', 'uses' => 'AcademicRegistrationFeeController@store']);
    Route::get('/edit/{id}', ['as' => 'academic_registration_fee.edit', 'uses' => 'AcademicRegistrationFeeController@edit']);
    Route::put('/update/{id}', ['as' => 'academic_registration_fee.update', 'uses' => 'AcademicRegistrationFeeController@update']);
    Route::delete('/delete/{id}', ['as' => 'academic_registration_fee.destroy', 'uses' => 'AcademicRegistrationFeeController@destroy']);
    
});
