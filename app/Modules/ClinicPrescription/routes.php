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
Route::group(['prefix' => 'clinic-prescription', 'namespace' => 'App\Modules\ClinicPrescription\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'clinic_prescription.index', 'uses' => 'ClinicPrescriptionController@index']);
    Route::any('/search', ['as' => 'clinic_prescription.search', 'uses' => 'ClinicPrescriptionController@search']);
    Route::get('/create', ['as' => 'clinic_prescription.create', 'uses' => 'ClinicPrescriptionController@create']);
    Route::get('/show/{id}', ['as' => 'clinic_prescription.show', 'uses' => 'ClinicPrescriptionController@show']);
    Route::get('/preview/{id}', ['as' => 'clinic_prescription.preview', 'uses' => 'ClinicPrescriptionController@preview']);
    Route::get('/print/{id}', ['as' => 'clinic_prescription.print', 'uses' => 'ClinicPrescriptionController@print']);

    Route::post('/store', ['as' => 'clinic_prescription.store', 'uses' => 'ClinicPrescriptionController@store']);
    Route::get('/edit/{id}', ['as' => 'clinic_prescription.edit', 'uses' => 'ClinicPrescriptionController@edit']);
    Route::put('/update/{id}', ['as' => 'clinic_prescription.update', 'uses' => 'ClinicPrescriptionController@update']);
    Route::delete('/delete/{id}', ['as' => 'clinic_prescription.destroy', 'uses' => 'ClinicPrescriptionController@destroy']);
    
});
