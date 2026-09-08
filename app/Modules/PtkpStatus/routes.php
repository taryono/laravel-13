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
Route::group(['prefix' => 'ptkp-status', 'namespace' => 'App\Modules\PtkpStatus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'ptkp_status.index', 'uses' => 'PtkpStatusController@index']);
    Route::any('/search', ['as' => 'ptkp_status.search', 'uses' => 'PtkpStatusController@search']);
    Route::get('/create', ['as' => 'ptkp_status.create', 'uses' => 'PtkpStatusController@create']);
    Route::get('/show/{id}', ['as' => 'ptkp_status.show', 'uses' => 'PtkpStatusController@show']);
    Route::get('/preview/{id}', ['as' => 'ptkp_status.preview', 'uses' => 'PtkpStatusController@preview']);
    Route::get('/print/{id}', ['as' => 'ptkp_status.print', 'uses' => 'PtkpStatusController@print']);

    Route::post('/store', ['as' => 'ptkp_status.store', 'uses' => 'PtkpStatusController@store']);
    Route::get('/edit/{id}', ['as' => 'ptkp_status.edit', 'uses' => 'PtkpStatusController@edit']);
    Route::put('/update/{id}', ['as' => 'ptkp_status.update', 'uses' => 'PtkpStatusController@update']);
    Route::delete('/delete/{id}', ['as' => 'ptkp_status.destroy', 'uses' => 'PtkpStatusController@destroy']);
    
});
