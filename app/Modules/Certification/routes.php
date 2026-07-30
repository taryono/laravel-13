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
Route::group(['prefix' => 'certification', 'namespace' => 'App\Modules\Certification\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', ['as' => 'certification.index', 'uses' => 'CertificationController@index']);
    Route::any('/search', ['as' => 'certification.search', 'uses' => 'CertificationController@search']);
    Route::get('/create', ['as' => 'certification.create', 'uses' => 'CertificationController@create']);
    Route::get('/show/{id}', ['as' => 'certification.show', 'uses' => 'CertificationController@show']);
    Route::get('/preview/{id}', ['as' => 'certification.preview', 'uses' => 'CertificationController@preview']);
    Route::get('/print/{id}', ['as' => 'certification.print', 'uses' => 'CertificationController@print']);

    Route::post('/store', ['as' => 'certification.store', 'uses' => 'CertificationController@store']);
    Route::get('/edit/{id}', ['as' => 'certification.edit', 'uses' => 'CertificationController@edit']);
    Route::put('/update/{id}', ['as' => 'certification.update', 'uses' => 'CertificationController@update']);
    Route::delete('/delete/{id}', ['as' => 'certification.destroy', 'uses' => 'CertificationController@destroy']);
    
});
