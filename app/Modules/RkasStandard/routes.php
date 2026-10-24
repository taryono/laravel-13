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
Route::group(['prefix' => 'rkas-standard', 'namespace' => 'App\Modules\RkasStandard\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_standard.index', 'uses' => 'RkasStandardController@index']);
    Route::any('/search', ['as' => 'rkas_standard.search', 'uses' => 'RkasStandardController@search']);
    Route::get('/create', ['as' => 'rkas_standard.create', 'uses' => 'RkasStandardController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_standard.show', 'uses' => 'RkasStandardController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_standard.preview', 'uses' => 'RkasStandardController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_standard.print', 'uses' => 'RkasStandardController@print']);

    Route::post('/store', ['as' => 'rkas_standard.store', 'uses' => 'RkasStandardController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_standard.edit', 'uses' => 'RkasStandardController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_standard.update', 'uses' => 'RkasStandardController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_standard.destroy', 'uses' => 'RkasStandardController@destroy']);
    
});
