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
Route::group(['prefix' => 'bos-component', 'namespace' => 'App\Modules\BosComponent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_component.index', 'uses' => 'BosComponentController@index']);
    Route::any('/search', ['as' => 'bos_component.search', 'uses' => 'BosComponentController@search']);
    Route::get('/create', ['as' => 'bos_component.create', 'uses' => 'BosComponentController@create']);
    Route::get('/show/{id}', ['as' => 'bos_component.show', 'uses' => 'BosComponentController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_component.preview', 'uses' => 'BosComponentController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_component.print', 'uses' => 'BosComponentController@print']);

    Route::post('/store', ['as' => 'bos_component.store', 'uses' => 'BosComponentController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_component.edit', 'uses' => 'BosComponentController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_component.update', 'uses' => 'BosComponentController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_component.destroy', 'uses' => 'BosComponentController@destroy']);
    
});
