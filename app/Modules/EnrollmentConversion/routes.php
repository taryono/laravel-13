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
Route::group(['prefix' => 'enrollment-conversion', 'namespace' => 'App\Modules\EnrollmentConversion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'enrollment_conversion.index', 'uses' => 'EnrollmentConversionController@index']);
    Route::any('/search', ['as' => 'enrollment_conversion.search', 'uses' => 'EnrollmentConversionController@search']);
    Route::get('/create', ['as' => 'enrollment_conversion.create', 'uses' => 'EnrollmentConversionController@create']);
    Route::get('/show/{id}', ['as' => 'enrollment_conversion.show', 'uses' => 'EnrollmentConversionController@show']);
    Route::get('/preview/{id}', ['as' => 'enrollment_conversion.preview', 'uses' => 'EnrollmentConversionController@preview']);
    Route::get('/print/{id}', ['as' => 'enrollment_conversion.print', 'uses' => 'EnrollmentConversionController@print']);

    Route::post('/store', ['as' => 'enrollment_conversion.store', 'uses' => 'EnrollmentConversionController@store']);
    Route::get('/edit/{id}', ['as' => 'enrollment_conversion.edit', 'uses' => 'EnrollmentConversionController@edit']);
    Route::put('/update/{id}', ['as' => 'enrollment_conversion.update', 'uses' => 'EnrollmentConversionController@update']);
    Route::delete('/delete/{id}', ['as' => 'enrollment_conversion.destroy', 'uses' => 'EnrollmentConversionController@destroy']);
    
});
