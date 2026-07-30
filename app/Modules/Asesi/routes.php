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
Route::group(['prefix' => 'asesi', 'namespace' => 'App\Modules\Asesi\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', ['as' => 'asesi.index', 'uses' => 'AsesiController@index']);
    Route::any('/search', ['as' => 'asesi.search', 'uses' => 'AsesiController@search']);
    Route::get('/create', ['as' => 'asesi.create', 'uses' => 'AsesiController@create']);
    Route::get('/show/{id}', ['as' => 'asesi.show', 'uses' => 'AsesiController@show']);
    Route::get('/preview/{id}', ['as' => 'asesi.preview', 'uses' => 'AsesiController@preview']);
    Route::get('/print/{id}', ['as' => 'asesi.print', 'uses' => 'AsesiController@print']);

    Route::post('/store', ['as' => 'asesi.store', 'uses' => 'AsesiController@store']);
    Route::get('/edit/{id}', ['as' => 'asesi.edit', 'uses' => 'AsesiController@edit']);
    Route::put('/update/{id}', ['as' => 'asesi.update', 'uses' => 'AsesiController@update']);
    Route::delete('/delete/{id}', ['as' => 'asesi.destroy', 'uses' => 'AsesiController@destroy']);    
});
