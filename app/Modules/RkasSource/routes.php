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
Route::group(['prefix' => 'rkas-source', 'namespace' => 'App\Modules\RkasSource\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_source.index', 'uses' => 'RkasSourceController@index']);
    Route::any('/search', ['as' => 'rkas_source.search', 'uses' => 'RkasSourceController@search']);
    Route::get('/create', ['as' => 'rkas_source.create', 'uses' => 'RkasSourceController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_source.show', 'uses' => 'RkasSourceController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_source.preview', 'uses' => 'RkasSourceController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_source.print', 'uses' => 'RkasSourceController@print']);

    Route::post('/store', ['as' => 'rkas_source.store', 'uses' => 'RkasSourceController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_source.edit', 'uses' => 'RkasSourceController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_source.update', 'uses' => 'RkasSourceController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_source.destroy', 'uses' => 'RkasSourceController@destroy']);
    
});
