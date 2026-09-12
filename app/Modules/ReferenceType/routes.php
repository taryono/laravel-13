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
Route::group(['prefix' => 'reference-type', 'namespace' => 'App\Modules\ReferenceType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'reference_type.index', 'uses' => 'ReferenceTypeController@index']);
    Route::any('/search', ['as' => 'reference_type.search', 'uses' => 'ReferenceTypeController@search']);
    Route::get('/create', ['as' => 'reference_type.create', 'uses' => 'ReferenceTypeController@create']);
    Route::get('/show/{id}', ['as' => 'reference_type.show', 'uses' => 'ReferenceTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'reference_type.preview', 'uses' => 'ReferenceTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'reference_type.print', 'uses' => 'ReferenceTypeController@print']);
    Route::get('/values/{id}', ['as' => 'reference_type.values', 'uses' => 'ReferenceTypeController@values']);
    Route::get('/data', ['as' => 'reference_type.data', 'uses' => 'ReferenceTypeController@data']);
    Route::post('/store', ['as' => 'reference_type.store', 'uses' => 'ReferenceTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'reference_type.edit', 'uses' => 'ReferenceTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'reference_type.update', 'uses' => 'ReferenceTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'reference_type.destroy', 'uses' => 'ReferenceTypeController@destroy']);
    
});
