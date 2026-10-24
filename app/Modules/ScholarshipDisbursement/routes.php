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
Route::group(['prefix' => 'scholarship-disbursement', 'namespace' => 'App\Modules\ScholarshipDisbursement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scholarship_disbursement.index', 'uses' => 'ScholarshipDisbursementController@index']);
    Route::any('/search', ['as' => 'scholarship_disbursement.search', 'uses' => 'ScholarshipDisbursementController@search']);
    Route::get('/create', ['as' => 'scholarship_disbursement.create', 'uses' => 'ScholarshipDisbursementController@create']);
    Route::get('/show/{id}', ['as' => 'scholarship_disbursement.show', 'uses' => 'ScholarshipDisbursementController@show']);
    Route::get('/preview/{id}', ['as' => 'scholarship_disbursement.preview', 'uses' => 'ScholarshipDisbursementController@preview']);
    Route::get('/print/{id}', ['as' => 'scholarship_disbursement.print', 'uses' => 'ScholarshipDisbursementController@print']);

    Route::post('/store', ['as' => 'scholarship_disbursement.store', 'uses' => 'ScholarshipDisbursementController@store']);
    Route::get('/edit/{id}', ['as' => 'scholarship_disbursement.edit', 'uses' => 'ScholarshipDisbursementController@edit']);
    Route::put('/update/{id}', ['as' => 'scholarship_disbursement.update', 'uses' => 'ScholarshipDisbursementController@update']);
    Route::delete('/delete/{id}', ['as' => 'scholarship_disbursement.destroy', 'uses' => 'ScholarshipDisbursementController@destroy']);
    
});
