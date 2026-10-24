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
Route::group(['prefix' => 'financial-aid-request', 'namespace' => 'App\Modules\FinancialAidRequest\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'financial_aid_request.index', 'uses' => 'FinancialAidRequestController@index']);
    Route::any('/search', ['as' => 'financial_aid_request.search', 'uses' => 'FinancialAidRequestController@search']);
    Route::get('/create', ['as' => 'financial_aid_request.create', 'uses' => 'FinancialAidRequestController@create']);
    Route::get('/show/{id}', ['as' => 'financial_aid_request.show', 'uses' => 'FinancialAidRequestController@show']);
    Route::get('/preview/{id}', ['as' => 'financial_aid_request.preview', 'uses' => 'FinancialAidRequestController@preview']);
    Route::get('/print/{id}', ['as' => 'financial_aid_request.print', 'uses' => 'FinancialAidRequestController@print']);

    Route::post('/store', ['as' => 'financial_aid_request.store', 'uses' => 'FinancialAidRequestController@store']);
    Route::get('/edit/{id}', ['as' => 'financial_aid_request.edit', 'uses' => 'FinancialAidRequestController@edit']);
    Route::put('/update/{id}', ['as' => 'financial_aid_request.update', 'uses' => 'FinancialAidRequestController@update']);
    Route::delete('/delete/{id}', ['as' => 'financial_aid_request.destroy', 'uses' => 'FinancialAidRequestController@destroy']);
    
});
