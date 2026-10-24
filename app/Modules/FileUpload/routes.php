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
Route::group(['prefix' => 'file-upload', 'namespace' => 'App\Modules\FileUpload\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'file_upload.index', 'uses' => 'FileUploadController@index']);
    Route::any('/search', ['as' => 'file_upload.search', 'uses' => 'FileUploadController@search']);
    Route::get('/create', ['as' => 'file_upload.create', 'uses' => 'FileUploadController@create']);
    Route::get('/show/{id}', ['as' => 'file_upload.show', 'uses' => 'FileUploadController@show']);
    Route::get('/preview/{id}', ['as' => 'file_upload.preview', 'uses' => 'FileUploadController@preview']);
    Route::get('/print/{id}', ['as' => 'file_upload.print', 'uses' => 'FileUploadController@print']);

    Route::post('/store', ['as' => 'file_upload.store', 'uses' => 'FileUploadController@store']);
    Route::get('/edit/{id}', ['as' => 'file_upload.edit', 'uses' => 'FileUploadController@edit']);
    Route::put('/update/{id}', ['as' => 'file_upload.update', 'uses' => 'FileUploadController@update']);
    Route::delete('/delete/{id}', ['as' => 'file_upload.destroy', 'uses' => 'FileUploadController@destroy']);
    
});
