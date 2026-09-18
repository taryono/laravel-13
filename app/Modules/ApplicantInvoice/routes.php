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
Route::group(['prefix' => 'applicant-invoice', 'namespace' => 'App\Modules\ApplicantInvoice\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_invoice.index', 'uses' => 'ApplicantInvoiceController@index']);
    Route::any('/search', ['as' => 'applicant_invoice.search', 'uses' => 'ApplicantInvoiceController@search']);
    Route::get('/create', ['as' => 'applicant_invoice.create', 'uses' => 'ApplicantInvoiceController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_invoice.show', 'uses' => 'ApplicantInvoiceController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_invoice.preview', 'uses' => 'ApplicantInvoiceController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_invoice.print', 'uses' => 'ApplicantInvoiceController@print']);

    Route::post('/store', ['as' => 'applicant_invoice.store', 'uses' => 'ApplicantInvoiceController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_invoice.edit', 'uses' => 'ApplicantInvoiceController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_invoice.update', 'uses' => 'ApplicantInvoiceController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_invoice.destroy', 'uses' => 'ApplicantInvoiceController@destroy']);
    
});
