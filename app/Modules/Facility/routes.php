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
Route::group(['prefix' => 'facility', 'namespace' => 'App\Modules\Facility\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'facility.index', 'uses' => 'FacilityController@index']);
    Route::any('/search', ['as' => 'facility.search', 'uses' => 'FacilityController@search']);
    Route::get('/create', ['as' => 'facility.create', 'uses' => 'FacilityController@create']);
    Route::get('/show/{id}', ['as' => 'facility.show', 'uses' => 'FacilityController@show']);
    Route::get('/preview/{id}', ['as' => 'facility.preview', 'uses' => 'FacilityController@preview']);
    Route::get('/print/{id}', ['as' => 'facility.print', 'uses' => 'FacilityController@print']);

    Route::post('/store', ['as' => 'facility.store', 'uses' => 'FacilityController@store']);
    Route::get('/edit/{id}', ['as' => 'facility.edit', 'uses' => 'FacilityController@edit']);
    Route::put('/update/{id}', ['as' => 'facility.update', 'uses' => 'FacilityController@update']);
    Route::delete('/delete/{id}', ['as' => 'facility.destroy', 'uses' => 'FacilityController@destroy']);
    
});
