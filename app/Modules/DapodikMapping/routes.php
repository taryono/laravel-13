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
Route::group(['prefix' => 'dapodik-mapping', 'namespace' => 'App\Modules\DapodikMapping\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'dapodik_mapping.index', 'uses' => 'DapodikMappingController@index']);
    Route::any('/search', ['as' => 'dapodik_mapping.search', 'uses' => 'DapodikMappingController@search']);
    Route::get('/create', ['as' => 'dapodik_mapping.create', 'uses' => 'DapodikMappingController@create']);
    Route::get('/show/{id}', ['as' => 'dapodik_mapping.show', 'uses' => 'DapodikMappingController@show']);
    Route::get('/preview/{id}', ['as' => 'dapodik_mapping.preview', 'uses' => 'DapodikMappingController@preview']);
    Route::get('/print/{id}', ['as' => 'dapodik_mapping.print', 'uses' => 'DapodikMappingController@print']);

    Route::post('/store', ['as' => 'dapodik_mapping.store', 'uses' => 'DapodikMappingController@store']);
    Route::get('/edit/{id}', ['as' => 'dapodik_mapping.edit', 'uses' => 'DapodikMappingController@edit']);
    Route::put('/update/{id}', ['as' => 'dapodik_mapping.update', 'uses' => 'DapodikMappingController@update']);
    Route::delete('/delete/{id}', ['as' => 'dapodik_mapping.destroy', 'uses' => 'DapodikMappingController@destroy']);
    
});
