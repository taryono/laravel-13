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
Route::group(['prefix' => 'marital-status', 'namespace' => 'App\Modules\MaritalStatus\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'marital_status.index', 'uses' => 'MaritalStatusController@index']);
    Route::any('/search', ['as' => 'marital_status.search', 'uses' => 'MaritalStatusController@search']);
    Route::get('/create', ['as' => 'marital_status.create', 'uses' => 'MaritalStatusController@create']);
    Route::get('/show/{id}', ['as' => 'marital_status.show', 'uses' => 'MaritalStatusController@show']);
    Route::get('/preview/{id}', ['as' => 'marital_status.preview', 'uses' => 'MaritalStatusController@preview']);
    Route::get('/print/{id}', ['as' => 'marital_status.print', 'uses' => 'MaritalStatusController@print']);

    Route::post('/store', ['as' => 'marital_status.store', 'uses' => 'MaritalStatusController@store']);
    Route::get('/edit/{id}', ['as' => 'marital_status.edit', 'uses' => 'MaritalStatusController@edit']);
    Route::put('/update/{id}', ['as' => 'marital_status.update', 'uses' => 'MaritalStatusController@update']);
    Route::delete('/delete/{id}', ['as' => 'marital_status.destroy', 'uses' => 'MaritalStatusController@destroy']);
    
});
