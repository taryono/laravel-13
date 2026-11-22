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
Route::group(['prefix' => 'record', 'namespace' => 'App\Modules\Record\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'record.index', 'uses' => 'RecordController@index']);
    Route::any('/search', ['as' => 'record.search', 'uses' => 'RecordController@search']);
    Route::get('/create', ['as' => 'record.create', 'uses' => 'RecordController@create']);
    Route::get('/show/{id}', ['as' => 'record.show', 'uses' => 'RecordController@show']);
    Route::get('/preview/{id}', ['as' => 'record.preview', 'uses' => 'RecordController@preview']);
    Route::get('/print/{id}', ['as' => 'record.print', 'uses' => 'RecordController@print']);

    Route::post('/store', ['as' => 'record.store', 'uses' => 'RecordController@store']);
    Route::get('/edit/{id}', ['as' => 'record.edit', 'uses' => 'RecordController@edit']);
    Route::put('/update/{id}', ['as' => 'record.update', 'uses' => 'RecordController@update']);
    Route::delete('/delete/{id}', ['as' => 'record.destroy', 'uses' => 'RecordController@destroy']);
    
});
