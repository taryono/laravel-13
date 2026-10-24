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
Route::group(['prefix' => 'report-card-signature', 'namespace' => 'App\Modules\ReportCardSignature\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_signature.index', 'uses' => 'ReportCardSignatureController@index']);
    Route::any('/search', ['as' => 'report_card_signature.search', 'uses' => 'ReportCardSignatureController@search']);
    Route::get('/create', ['as' => 'report_card_signature.create', 'uses' => 'ReportCardSignatureController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_signature.show', 'uses' => 'ReportCardSignatureController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_signature.preview', 'uses' => 'ReportCardSignatureController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_signature.print', 'uses' => 'ReportCardSignatureController@print']);

    Route::post('/store', ['as' => 'report_card_signature.store', 'uses' => 'ReportCardSignatureController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_signature.edit', 'uses' => 'ReportCardSignatureController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_signature.update', 'uses' => 'ReportCardSignatureController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_signature.destroy', 'uses' => 'ReportCardSignatureController@destroy']);
    
});
