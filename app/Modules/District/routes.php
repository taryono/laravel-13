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
Route::group(['prefix' => 'district', 'namespace' => 'App\Modules\District\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'district.index', 'uses' => 'DistrictController@index']);
    Route::any('/search', ['as' => 'district.search', 'uses' => 'DistrictController@search']);
    Route::get('/create', ['as' => 'district.create', 'uses' => 'DistrictController@create']);
    Route::get('/show/{id}', ['as' => 'district.show', 'uses' => 'DistrictController@show']);
    Route::get('/preview/{id}', ['as' => 'district.preview', 'uses' => 'DistrictController@preview']);
    Route::get('/print/{id}', ['as' => 'district.print', 'uses' => 'DistrictController@print']);

    Route::post('/store', ['as' => 'district.store', 'uses' => 'DistrictController@store']);
    Route::get('/edit/{id}', ['as' => 'district.edit', 'uses' => 'DistrictController@edit']);
    Route::put('/update/{id}', ['as' => 'district.update', 'uses' => 'DistrictController@update']);
    Route::delete('/delete/{id}', ['as' => 'district.destroy', 'uses' => 'DistrictController@destroy']);
    
});
