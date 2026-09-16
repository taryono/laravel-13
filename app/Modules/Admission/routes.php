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
Route::group(['prefix' => 'admission', 'namespace' => 'App\Modules\Admission\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission.index', 'uses' => 'AdmissionController@index']);
    Route::any('/search', ['as' => 'admission.search', 'uses' => 'AdmissionController@search']);
    Route::get('/create', ['as' => 'admission.create', 'uses' => 'AdmissionController@create']);
    Route::get('/show/{id}', ['as' => 'admission.show', 'uses' => 'AdmissionController@show']);
    Route::get('/preview/{id}', ['as' => 'admission.preview', 'uses' => 'AdmissionController@preview']);
    Route::get('/print/{id}', ['as' => 'admission.print', 'uses' => 'AdmissionController@print']);

    Route::post('/store', ['as' => 'admission.store', 'uses' => 'AdmissionController@store']);
    Route::get('/edit/{id}', ['as' => 'admission.edit', 'uses' => 'AdmissionController@edit']);
    Route::put('/update/{id}', ['as' => 'admission.update', 'uses' => 'AdmissionController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission.destroy', 'uses' => 'AdmissionController@destroy']);
    
});
