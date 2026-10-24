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
Route::group(['prefix' => 'clinic-visit', 'namespace' => 'App\Modules\ClinicVisit\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'clinic_visit.index', 'uses' => 'ClinicVisitController@index']);
    Route::any('/search', ['as' => 'clinic_visit.search', 'uses' => 'ClinicVisitController@search']);
    Route::get('/create', ['as' => 'clinic_visit.create', 'uses' => 'ClinicVisitController@create']);
    Route::get('/show/{id}', ['as' => 'clinic_visit.show', 'uses' => 'ClinicVisitController@show']);
    Route::get('/preview/{id}', ['as' => 'clinic_visit.preview', 'uses' => 'ClinicVisitController@preview']);
    Route::get('/print/{id}', ['as' => 'clinic_visit.print', 'uses' => 'ClinicVisitController@print']);

    Route::post('/store', ['as' => 'clinic_visit.store', 'uses' => 'ClinicVisitController@store']);
    Route::get('/edit/{id}', ['as' => 'clinic_visit.edit', 'uses' => 'ClinicVisitController@edit']);
    Route::put('/update/{id}', ['as' => 'clinic_visit.update', 'uses' => 'ClinicVisitController@update']);
    Route::delete('/delete/{id}', ['as' => 'clinic_visit.destroy', 'uses' => 'ClinicVisitController@destroy']);
    
});
