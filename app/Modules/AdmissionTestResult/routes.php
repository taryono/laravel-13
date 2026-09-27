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
Route::group(['prefix' => 'admission-test-result', 'namespace' => 'App\Modules\AdmissionTestResult\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_test_result.index', 'uses' => 'AdmissionTestResultController@index']);
    Route::any('/search', ['as' => 'admission_test_result.search', 'uses' => 'AdmissionTestResultController@search']);
    Route::get('/create', ['as' => 'admission_test_result.create', 'uses' => 'AdmissionTestResultController@create']);
    Route::get('/show/{id}', ['as' => 'admission_test_result.show', 'uses' => 'AdmissionTestResultController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_test_result.preview', 'uses' => 'AdmissionTestResultController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_test_result.print', 'uses' => 'AdmissionTestResultController@print']);

    Route::post('/store', ['as' => 'admission_test_result.store', 'uses' => 'AdmissionTestResultController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_test_result.edit', 'uses' => 'AdmissionTestResultController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_test_result.update', 'uses' => 'AdmissionTestResultController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_test_result.destroy', 'uses' => 'AdmissionTestResultController@destroy']);
    
});
