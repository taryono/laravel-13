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
Route::group(['prefix' => 'teaching-load', 'namespace' => 'App\Modules\TeachingLoad\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'teaching_load.index', 'uses' => 'TeachingLoadController@index']);
    Route::any('/search', ['as' => 'teaching_load.search', 'uses' => 'TeachingLoadController@search']);
    Route::get('/create', ['as' => 'teaching_load.create', 'uses' => 'TeachingLoadController@create']);
    Route::get('/show/{id}', ['as' => 'teaching_load.show', 'uses' => 'TeachingLoadController@show']);
    Route::get('/preview/{id}', ['as' => 'teaching_load.preview', 'uses' => 'TeachingLoadController@preview']);
    Route::get('/print/{id}', ['as' => 'teaching_load.print', 'uses' => 'TeachingLoadController@print']);

    Route::post('/store', ['as' => 'teaching_load.store', 'uses' => 'TeachingLoadController@store']);
    Route::get('/edit/{id}', ['as' => 'teaching_load.edit', 'uses' => 'TeachingLoadController@edit']);
    Route::put('/update/{id}', ['as' => 'teaching_load.update', 'uses' => 'TeachingLoadController@update']);
    Route::delete('/delete/{id}', ['as' => 'teaching_load.destroy', 'uses' => 'TeachingLoadController@destroy']);
    
});
