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
Route::group(['prefix' => 'region', 'namespace' => 'App\Modules\Region\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'region.index', 'uses' => 'RegionController@index']);
    Route::any('/search', ['as' => 'region.search', 'uses' => 'RegionController@search']);
    Route::get('/create', ['as' => 'region.create', 'uses' => 'RegionController@create']);
    Route::get('/show/{id}', ['as' => 'region.show', 'uses' => 'RegionController@show']);
    Route::get('/preview/{id}', ['as' => 'region.preview', 'uses' => 'RegionController@preview']);
    Route::get('/print/{id}', ['as' => 'region.print', 'uses' => 'RegionController@print']);

    Route::post('/store', ['as' => 'region.store', 'uses' => 'RegionController@store']);
    Route::get('/edit/{id}', ['as' => 'region.edit', 'uses' => 'RegionController@edit']);
    Route::put('/update/{id}', ['as' => 'region.update', 'uses' => 'RegionController@update']);
    Route::delete('/delete/{id}', ['as' => 'region.destroy', 'uses' => 'RegionController@destroy']);
    
});
