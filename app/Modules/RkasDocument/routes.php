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
Route::group(['prefix' => 'rkas-document', 'namespace' => 'App\Modules\RkasDocument\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_document.index', 'uses' => 'RkasDocumentController@index']);
    Route::any('/search', ['as' => 'rkas_document.search', 'uses' => 'RkasDocumentController@search']);
    Route::get('/create', ['as' => 'rkas_document.create', 'uses' => 'RkasDocumentController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_document.show', 'uses' => 'RkasDocumentController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_document.preview', 'uses' => 'RkasDocumentController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_document.print', 'uses' => 'RkasDocumentController@print']);

    Route::post('/store', ['as' => 'rkas_document.store', 'uses' => 'RkasDocumentController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_document.edit', 'uses' => 'RkasDocumentController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_document.update', 'uses' => 'RkasDocumentController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_document.destroy', 'uses' => 'RkasDocumentController@destroy']);
    
});
