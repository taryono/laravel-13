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
Route::group(['prefix' => 'admission-path', 'namespace' => 'App\Modules\AdmissionPath\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_path.index', 'uses' => 'AdmissionPathController@index']);
    Route::any('/search', ['as' => 'admission_path.search', 'uses' => 'AdmissionPathController@search']);
    Route::get('/create', ['as' => 'admission_path.create', 'uses' => 'AdmissionPathController@create']);
    Route::get('/show/{id}', ['as' => 'admission_path.show', 'uses' => 'AdmissionPathController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_path.preview', 'uses' => 'AdmissionPathController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_path.print', 'uses' => 'AdmissionPathController@print']);

    Route::post('/store', ['as' => 'admission_path.store', 'uses' => 'AdmissionPathController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_path.edit', 'uses' => 'AdmissionPathController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_path.update', 'uses' => 'AdmissionPathController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_path.destroy', 'uses' => 'AdmissionPathController@destroy']);
    
});
