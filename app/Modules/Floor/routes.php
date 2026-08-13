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
Route::group(['prefix' => 'floor', 'namespace' => 'App\Modules\Floor\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'floor.index', 'uses' => 'FloorController@index']);
    Route::any('/search', ['as' => 'floor.search', 'uses' => 'FloorController@search']);
    Route::get('/create', ['as' => 'floor.create', 'uses' => 'FloorController@create']);
    Route::get('/show/{id}', ['as' => 'floor.show', 'uses' => 'FloorController@show']);
    Route::get('/preview/{id}', ['as' => 'floor.preview', 'uses' => 'FloorController@preview']);
    Route::get('/print/{id}', ['as' => 'floor.print', 'uses' => 'FloorController@print']);

    Route::post('/store', ['as' => 'floor.store', 'uses' => 'FloorController@store']);
    Route::get('/edit/{id}', ['as' => 'floor.edit', 'uses' => 'FloorController@edit']);
    Route::put('/update/{id}', ['as' => 'floor.update', 'uses' => 'FloorController@update']);
    Route::delete('/delete/{id}', ['as' => 'floor.destroy', 'uses' => 'FloorController@destroy']);
    
});
