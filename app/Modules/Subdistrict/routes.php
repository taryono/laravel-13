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
Route::group(['prefix' => 'subdistrict', 'namespace' => 'App\Modules\Subdistrict\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'subdistrict.index', 'uses' => 'SubdistrictController@index']);
    Route::any('/search', ['as' => 'subdistrict.search', 'uses' => 'SubdistrictController@search']);
    Route::get('/create', ['as' => 'subdistrict.create', 'uses' => 'SubdistrictController@create']);
    Route::get('/show/{id}', ['as' => 'subdistrict.show', 'uses' => 'SubdistrictController@show']);
    Route::get('/preview/{id}', ['as' => 'subdistrict.preview', 'uses' => 'SubdistrictController@preview']);
    Route::get('/print/{id}', ['as' => 'subdistrict.print', 'uses' => 'SubdistrictController@print']);

    Route::post('/store', ['as' => 'subdistrict.store', 'uses' => 'SubdistrictController@store']);
    Route::get('/edit/{id}', ['as' => 'subdistrict.edit', 'uses' => 'SubdistrictController@edit']);
    Route::put('/update/{id}', ['as' => 'subdistrict.update', 'uses' => 'SubdistrictController@update']);
    Route::delete('/delete/{id}', ['as' => 'subdistrict.destroy', 'uses' => 'SubdistrictController@destroy']);
    
});
