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
Route::group(['prefix' => 'admission-document-verification', 'namespace' => 'App\Modules\AdmissionDocumentVerification\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_document_verification.index', 'uses' => 'AdmissionDocumentVerificationController@index']);
    Route::any('/search', ['as' => 'admission_document_verification.search', 'uses' => 'AdmissionDocumentVerificationController@search']);
    Route::get('/create', ['as' => 'admission_document_verification.create', 'uses' => 'AdmissionDocumentVerificationController@create']);
    Route::get('/show/{id}', ['as' => 'admission_document_verification.show', 'uses' => 'AdmissionDocumentVerificationController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_document_verification.preview', 'uses' => 'AdmissionDocumentVerificationController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_document_verification.print', 'uses' => 'AdmissionDocumentVerificationController@print']);

    Route::post('/store', ['as' => 'admission_document_verification.store', 'uses' => 'AdmissionDocumentVerificationController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_document_verification.edit', 'uses' => 'AdmissionDocumentVerificationController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_document_verification.update', 'uses' => 'AdmissionDocumentVerificationController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_document_verification.destroy', 'uses' => 'AdmissionDocumentVerificationController@destroy']);
    
});
