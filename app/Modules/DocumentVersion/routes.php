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
Route::group(['prefix' => 'document-version', 'namespace' => 'App\Modules\DocumentVersion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'document_version.index', 'uses' => 'DocumentVersionController@index']);
    Route::any('/search', ['as' => 'document_version.search', 'uses' => 'DocumentVersionController@search']);
    Route::get('/create', ['as' => 'document_version.create', 'uses' => 'DocumentVersionController@create']);
    Route::get('/show/{id}', ['as' => 'document_version.show', 'uses' => 'DocumentVersionController@show']);
    Route::get('/preview/{id}', ['as' => 'document_version.preview', 'uses' => 'DocumentVersionController@preview']);
    Route::get('/print/{id}', ['as' => 'document_version.print', 'uses' => 'DocumentVersionController@print']);

    Route::post('/store', ['as' => 'document_version.store', 'uses' => 'DocumentVersionController@store']);
    Route::get('/edit/{id}', ['as' => 'document_version.edit', 'uses' => 'DocumentVersionController@edit']);
    Route::put('/update/{id}', ['as' => 'document_version.update', 'uses' => 'DocumentVersionController@update']);
    Route::delete('/delete/{id}', ['as' => 'document_version.destroy', 'uses' => 'DocumentVersionController@destroy']);
    
});
