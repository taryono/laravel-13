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
Route::group(['prefix' => 'admission-selection', 'namespace' => 'App\Modules\AdmissionSelection\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_selection.index', 'uses' => 'AdmissionSelectionController@index']);
    Route::any('/search', ['as' => 'admission_selection.search', 'uses' => 'AdmissionSelectionController@search']);
    Route::get('/create', ['as' => 'admission_selection.create', 'uses' => 'AdmissionSelectionController@create']);
    Route::get('/show/{id}', ['as' => 'admission_selection.show', 'uses' => 'AdmissionSelectionController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_selection.preview', 'uses' => 'AdmissionSelectionController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_selection.print', 'uses' => 'AdmissionSelectionController@print']);

    Route::post('/store', ['as' => 'admission_selection.store', 'uses' => 'AdmissionSelectionController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_selection.edit', 'uses' => 'AdmissionSelectionController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_selection.update', 'uses' => 'AdmissionSelectionController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_selection.destroy', 'uses' => 'AdmissionSelectionController@destroy']);
    
});
