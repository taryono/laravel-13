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
Route::group(['prefix' => 'test-type', 'namespace' => 'App\Modules\TestType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'test_type.index', 'uses' => 'TestTypeController@index']);
    Route::any('/search', ['as' => 'test_type.search', 'uses' => 'TestTypeController@search']);
    Route::get('/create', ['as' => 'test_type.create', 'uses' => 'TestTypeController@create']);
    Route::get('/show/{id}', ['as' => 'test_type.show', 'uses' => 'TestTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'test_type.preview', 'uses' => 'TestTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'test_type.print', 'uses' => 'TestTypeController@print']);

    Route::post('/store', ['as' => 'test_type.store', 'uses' => 'TestTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'test_type.edit', 'uses' => 'TestTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'test_type.update', 'uses' => 'TestTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'test_type.destroy', 'uses' => 'TestTypeController@destroy']);
    
});
