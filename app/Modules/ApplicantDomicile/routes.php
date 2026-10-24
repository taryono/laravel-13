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
Route::group(['prefix' => 'applicant-domicile', 'namespace' => 'App\Modules\ApplicantDomicile\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_domicile.index', 'uses' => 'ApplicantDomicileController@index']);
    Route::any('/search', ['as' => 'applicant_domicile.search', 'uses' => 'ApplicantDomicileController@search']);
    Route::get('/create', ['as' => 'applicant_domicile.create', 'uses' => 'ApplicantDomicileController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_domicile.show', 'uses' => 'ApplicantDomicileController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_domicile.preview', 'uses' => 'ApplicantDomicileController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_domicile.print', 'uses' => 'ApplicantDomicileController@print']);

    Route::post('/store', ['as' => 'applicant_domicile.store', 'uses' => 'ApplicantDomicileController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_domicile.edit', 'uses' => 'ApplicantDomicileController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_domicile.update', 'uses' => 'ApplicantDomicileController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_domicile.destroy', 'uses' => 'ApplicantDomicileController@destroy']);
    
});
