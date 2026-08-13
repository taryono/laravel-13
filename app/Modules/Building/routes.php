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
Route::group(['prefix' => 'building', 'namespace' => 'App\Modules\Building\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'building.index', 'uses' => 'BuildingController@index']);
    Route::any('/search', ['as' => 'building.search', 'uses' => 'BuildingController@search']);
    Route::get('/create', ['as' => 'building.create', 'uses' => 'BuildingController@create']);
    Route::get('/show/{id}', ['as' => 'building.show', 'uses' => 'BuildingController@show']);
    Route::get('/preview/{id}', ['as' => 'building.preview', 'uses' => 'BuildingController@preview']);
    Route::get('/print/{id}', ['as' => 'building.print', 'uses' => 'BuildingController@print']);

    Route::post('/store', ['as' => 'building.store', 'uses' => 'BuildingController@store']);
    Route::get('/edit/{id}', ['as' => 'building.edit', 'uses' => 'BuildingController@edit']);
    Route::put('/update/{id}', ['as' => 'building.update', 'uses' => 'BuildingController@update']);
    Route::delete('/delete/{id}', ['as' => 'building.destroy', 'uses' => 'BuildingController@destroy']);
    
});
