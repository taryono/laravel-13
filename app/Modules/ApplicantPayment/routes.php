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
Route::group(['prefix' => 'applicant-payment', 'namespace' => 'App\Modules\ApplicantPayment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_payment.index', 'uses' => 'ApplicantPaymentController@index']);
    Route::any('/search', ['as' => 'applicant_payment.search', 'uses' => 'ApplicantPaymentController@search']);
    Route::get('/create', ['as' => 'applicant_payment.create', 'uses' => 'ApplicantPaymentController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_payment.show', 'uses' => 'ApplicantPaymentController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_payment.preview', 'uses' => 'ApplicantPaymentController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_payment.print', 'uses' => 'ApplicantPaymentController@print']);

    Route::post('/store', ['as' => 'applicant_payment.store', 'uses' => 'ApplicantPaymentController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_payment.edit', 'uses' => 'ApplicantPaymentController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_payment.update', 'uses' => 'ApplicantPaymentController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_payment.destroy', 'uses' => 'ApplicantPaymentController@destroy']);
    
});
