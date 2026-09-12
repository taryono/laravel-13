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
Route::group(['prefix' => 'reference-value', 'namespace' => 'App\Modules\ReferenceValue\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'reference_value.index', 'uses' => 'ReferenceValueController@index']);
    Route::any('/search', ['as' => 'reference_value.search', 'uses' => 'ReferenceValueController@search']);
    Route::get('/create', ['as' => 'reference_value.create', 'uses' => 'ReferenceValueController@create']);
    Route::get('/show/{id}', ['as' => 'reference_value.show', 'uses' => 'ReferenceValueController@show']);
    Route::get('/preview/{id}', ['as' => 'reference_value.preview', 'uses' => 'ReferenceValueController@preview']);
    Route::get('/print/{id}', ['as' => 'reference_value.print', 'uses' => 'ReferenceValueController@print']);

    Route::post('/store', ['as' => 'reference_value.store', 'uses' => 'ReferenceValueController@store']);
    Route::get('/edit/{id}', ['as' => 'reference_value.edit', 'uses' => 'ReferenceValueController@edit']);
    Route::put('/update/{id}', ['as' => 'reference_value.update', 'uses' => 'ReferenceValueController@update']);
    Route::delete('/delete/{id}', ['as' => 'reference_value.destroy', 'uses' => 'ReferenceValueController@destroy']);
    
});
