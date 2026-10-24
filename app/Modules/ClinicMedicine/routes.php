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
Route::group(['prefix' => 'clinic-medicine', 'namespace' => 'App\Modules\ClinicMedicine\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'clinic_medicine.index', 'uses' => 'ClinicMedicineController@index']);
    Route::any('/search', ['as' => 'clinic_medicine.search', 'uses' => 'ClinicMedicineController@search']);
    Route::get('/create', ['as' => 'clinic_medicine.create', 'uses' => 'ClinicMedicineController@create']);
    Route::get('/show/{id}', ['as' => 'clinic_medicine.show', 'uses' => 'ClinicMedicineController@show']);
    Route::get('/preview/{id}', ['as' => 'clinic_medicine.preview', 'uses' => 'ClinicMedicineController@preview']);
    Route::get('/print/{id}', ['as' => 'clinic_medicine.print', 'uses' => 'ClinicMedicineController@print']);

    Route::post('/store', ['as' => 'clinic_medicine.store', 'uses' => 'ClinicMedicineController@store']);
    Route::get('/edit/{id}', ['as' => 'clinic_medicine.edit', 'uses' => 'ClinicMedicineController@edit']);
    Route::put('/update/{id}', ['as' => 'clinic_medicine.update', 'uses' => 'ClinicMedicineController@update']);
    Route::delete('/delete/{id}', ['as' => 'clinic_medicine.destroy', 'uses' => 'ClinicMedicineController@destroy']);
    
});
