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
Route::group(['prefix' => 'bos-realization', 'namespace' => 'App\Modules\BosRealization\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'bos_realization.index', 'uses' => 'BosRealizationController@index']);
    Route::any('/search', ['as' => 'bos_realization.search', 'uses' => 'BosRealizationController@search']);
    Route::get('/create', ['as' => 'bos_realization.create', 'uses' => 'BosRealizationController@create']);
    Route::get('/show/{id}', ['as' => 'bos_realization.show', 'uses' => 'BosRealizationController@show']);
    Route::get('/preview/{id}', ['as' => 'bos_realization.preview', 'uses' => 'BosRealizationController@preview']);
    Route::get('/print/{id}', ['as' => 'bos_realization.print', 'uses' => 'BosRealizationController@print']);

    Route::post('/store', ['as' => 'bos_realization.store', 'uses' => 'BosRealizationController@store']);
    Route::get('/edit/{id}', ['as' => 'bos_realization.edit', 'uses' => 'BosRealizationController@edit']);
    Route::put('/update/{id}', ['as' => 'bos_realization.update', 'uses' => 'BosRealizationController@update']);
    Route::delete('/delete/{id}', ['as' => 'bos_realization.destroy', 'uses' => 'BosRealizationController@destroy']);
    
});
