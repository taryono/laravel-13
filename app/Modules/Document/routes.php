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
Route::group(['prefix' => 'document', 'namespace' => 'App\Modules\Document\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'document.index', 'uses' => 'DocumentController@index']);
    Route::any('/search', ['as' => 'document.search', 'uses' => 'DocumentController@search']);
    Route::get('/create', ['as' => 'document.create', 'uses' => 'DocumentController@create']);
    Route::get('/show/{id}', ['as' => 'document.show', 'uses' => 'DocumentController@show']);
    Route::get('/preview/{id}', ['as' => 'document.preview', 'uses' => 'DocumentController@preview']);
    Route::get('/print/{id}', ['as' => 'document.print', 'uses' => 'DocumentController@print']);

    Route::post('/store', ['as' => 'document.store', 'uses' => 'DocumentController@store']);
    Route::get('/edit/{id}', ['as' => 'document.edit', 'uses' => 'DocumentController@edit']);
    Route::put('/update/{id}', ['as' => 'document.update', 'uses' => 'DocumentController@update']);
    Route::delete('/delete/{id}', ['as' => 'document.destroy', 'uses' => 'DocumentController@destroy']);
    
});
