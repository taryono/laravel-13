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
Route::group(['prefix' => 'data-import', 'namespace' => 'App\Modules\DataImport\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'data_import.index', 'uses' => 'DataImportController@index']);
    Route::any('/search', ['as' => 'data_import.search', 'uses' => 'DataImportController@search']);
    Route::get('/create', ['as' => 'data_import.create', 'uses' => 'DataImportController@create']);
    Route::get('/show/{id}', ['as' => 'data_import.show', 'uses' => 'DataImportController@show']);
    Route::get('/preview/{id}', ['as' => 'data_import.preview', 'uses' => 'DataImportController@preview']);
    Route::get('/print/{id}', ['as' => 'data_import.print', 'uses' => 'DataImportController@print']);

    Route::post('/store', ['as' => 'data_import.store', 'uses' => 'DataImportController@store']);
    Route::get('/edit/{id}', ['as' => 'data_import.edit', 'uses' => 'DataImportController@edit']);
    Route::put('/update/{id}', ['as' => 'data_import.update', 'uses' => 'DataImportController@update']);
    Route::delete('/delete/{id}', ['as' => 'data_import.destroy', 'uses' => 'DataImportController@destroy']);
    
});
