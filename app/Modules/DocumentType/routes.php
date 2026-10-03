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
Route::group(['prefix' => 'document-type', 'namespace' => 'App\Modules\DocumentType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'document_type.index', 'uses' => 'DocumentTypeController@index']);
    Route::any('/search', ['as' => 'document_type.search', 'uses' => 'DocumentTypeController@search']);
    Route::get('/create', ['as' => 'document_type.create', 'uses' => 'DocumentTypeController@create']);
    Route::get('/show/{id}', ['as' => 'document_type.show', 'uses' => 'DocumentTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'document_type.preview', 'uses' => 'DocumentTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'document_type.print', 'uses' => 'DocumentTypeController@print']);

    Route::post('/store', ['as' => 'document_type.store', 'uses' => 'DocumentTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'document_type.edit', 'uses' => 'DocumentTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'document_type.update', 'uses' => 'DocumentTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'document_type.destroy', 'uses' => 'DocumentTypeController@destroy']);
    
});
