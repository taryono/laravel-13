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
Route::group(['prefix' => 'rkas-realization', 'namespace' => 'App\Modules\RkasRealization\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_realization.index', 'uses' => 'RkasRealizationController@index']);
    Route::any('/search', ['as' => 'rkas_realization.search', 'uses' => 'RkasRealizationController@search']);
    Route::get('/create', ['as' => 'rkas_realization.create', 'uses' => 'RkasRealizationController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_realization.show', 'uses' => 'RkasRealizationController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_realization.preview', 'uses' => 'RkasRealizationController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_realization.print', 'uses' => 'RkasRealizationController@print']);

    Route::post('/store', ['as' => 'rkas_realization.store', 'uses' => 'RkasRealizationController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_realization.edit', 'uses' => 'RkasRealizationController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_realization.update', 'uses' => 'RkasRealizationController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_realization.destroy', 'uses' => 'RkasRealizationController@destroy']);
    
});
