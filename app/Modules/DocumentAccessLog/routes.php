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
Route::group(['prefix' => 'document-access-log', 'namespace' => 'App\Modules\DocumentAccessLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'document_access_log.index', 'uses' => 'DocumentAccessLogController@index']);
    Route::any('/search', ['as' => 'document_access_log.search', 'uses' => 'DocumentAccessLogController@search']);
    Route::get('/create', ['as' => 'document_access_log.create', 'uses' => 'DocumentAccessLogController@create']);
    Route::get('/show/{id}', ['as' => 'document_access_log.show', 'uses' => 'DocumentAccessLogController@show']);
    Route::get('/preview/{id}', ['as' => 'document_access_log.preview', 'uses' => 'DocumentAccessLogController@preview']);
    Route::get('/print/{id}', ['as' => 'document_access_log.print', 'uses' => 'DocumentAccessLogController@print']);

    Route::post('/store', ['as' => 'document_access_log.store', 'uses' => 'DocumentAccessLogController@store']);
    Route::get('/edit/{id}', ['as' => 'document_access_log.edit', 'uses' => 'DocumentAccessLogController@edit']);
    Route::put('/update/{id}', ['as' => 'document_access_log.update', 'uses' => 'DocumentAccessLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'document_access_log.destroy', 'uses' => 'DocumentAccessLogController@destroy']);
    
});
