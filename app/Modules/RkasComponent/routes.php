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
Route::group(['prefix' => 'rkas-component', 'namespace' => 'App\Modules\RkasComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_component.index', 'uses' => 'RkasComponentController@index']);
    Route::any('/search', ['as' => 'rkas_component.search', 'uses' => 'RkasComponentController@search']);
    Route::get('/create', ['as' => 'rkas_component.create', 'uses' => 'RkasComponentController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_component.show', 'uses' => 'RkasComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_component.preview', 'uses' => 'RkasComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_component.print', 'uses' => 'RkasComponentController@print']);

    Route::post('/store', ['as' => 'rkas_component.store', 'uses' => 'RkasComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_component.edit', 'uses' => 'RkasComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_component.update', 'uses' => 'RkasComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_component.destroy', 'uses' => 'RkasComponentController@destroy']);
    
});
