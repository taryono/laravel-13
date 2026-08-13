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
Route::group(['prefix' => 'dictionary', 'namespace' => 'App\Modules\Dictionary\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dictionary.index', 'uses' => 'DictionaryController@index']);
    Route::any('/search', ['as' => 'dictionary.search', 'uses' => 'DictionaryController@search']);
    Route::get('/create', ['as' => 'dictionary.create', 'uses' => 'DictionaryController@create']);
    Route::get('/show/{id}', ['as' => 'dictionary.show', 'uses' => 'DictionaryController@show']);
    Route::get('/preview/{id}', ['as' => 'dictionary.preview', 'uses' => 'DictionaryController@preview']);
    Route::get('/print/{id}', ['as' => 'dictionary.print', 'uses' => 'DictionaryController@print']);

    Route::post('/store', ['as' => 'dictionary.store', 'uses' => 'DictionaryController@store']);
    Route::get('/edit/{id}', ['as' => 'dictionary.edit', 'uses' => 'DictionaryController@edit']);
    Route::put('/update/{id}', ['as' => 'dictionary.update', 'uses' => 'DictionaryController@update']);
    Route::delete('/delete/{id}', ['as' => 'dictionary.destroy', 'uses' => 'DictionaryController@destroy']);
    
});
