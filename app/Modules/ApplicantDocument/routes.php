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
Route::group(['prefix' => 'applicant-document', 'namespace' => 'App\Modules\ApplicantDocument\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_document.index', 'uses' => 'ApplicantDocumentController@index']);
    Route::any('/search', ['as' => 'applicant_document.search', 'uses' => 'ApplicantDocumentController@search']);
    Route::get('/create', ['as' => 'applicant_document.create', 'uses' => 'ApplicantDocumentController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_document.show', 'uses' => 'ApplicantDocumentController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_document.preview', 'uses' => 'ApplicantDocumentController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_document.print', 'uses' => 'ApplicantDocumentController@print']);

    Route::post('/store', ['as' => 'applicant_document.store', 'uses' => 'ApplicantDocumentController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_document.edit', 'uses' => 'ApplicantDocumentController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_document.update', 'uses' => 'ApplicantDocumentController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_document.destroy', 'uses' => 'ApplicantDocumentController@destroy']);
    
});
