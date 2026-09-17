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
Route::group(['prefix' => 'application-document', 'namespace' => 'App\Modules\ApplicationDocument\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'application_document.index', 'uses' => 'ApplicationDocumentController@index']);
    Route::any('/search', ['as' => 'application_document.search', 'uses' => 'ApplicationDocumentController@search']);
    Route::get('/create', ['as' => 'application_document.create', 'uses' => 'ApplicationDocumentController@create']);
    Route::get('/show/{id}', ['as' => 'application_document.show', 'uses' => 'ApplicationDocumentController@show']);
    Route::get('/preview/{id}', ['as' => 'application_document.preview', 'uses' => 'ApplicationDocumentController@preview']);
    Route::get('/print/{id}', ['as' => 'application_document.print', 'uses' => 'ApplicationDocumentController@print']);

    Route::post('/store', ['as' => 'application_document.store', 'uses' => 'ApplicationDocumentController@store']);
    Route::get('/edit/{id}', ['as' => 'application_document.edit', 'uses' => 'ApplicationDocumentController@edit']);
    Route::put('/update/{id}', ['as' => 'application_document.update', 'uses' => 'ApplicationDocumentController@update']);
    Route::delete('/delete/{id}', ['as' => 'application_document.destroy', 'uses' => 'ApplicationDocumentController@destroy']);
    
});
