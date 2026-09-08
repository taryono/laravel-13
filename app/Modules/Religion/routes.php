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
Route::group(['prefix' => 'religion', 'namespace' => 'App\Modules\Religion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'religion.index', 'uses' => 'ReligionController@index']);
    Route::any('/search', ['as' => 'religion.search', 'uses' => 'ReligionController@search']);
    Route::get('/create', ['as' => 'religion.create', 'uses' => 'ReligionController@create']);
    Route::get('/show/{id}', ['as' => 'religion.show', 'uses' => 'ReligionController@show']);
    Route::get('/preview/{id}', ['as' => 'religion.preview', 'uses' => 'ReligionController@preview']);
    Route::get('/print/{id}', ['as' => 'religion.print', 'uses' => 'ReligionController@print']);

    Route::post('/store', ['as' => 'religion.store', 'uses' => 'ReligionController@store']);
    Route::get('/edit/{id}', ['as' => 'religion.edit', 'uses' => 'ReligionController@edit']);
    Route::put('/update/{id}', ['as' => 'religion.update', 'uses' => 'ReligionController@update']);
    Route::delete('/delete/{id}', ['as' => 'religion.destroy', 'uses' => 'ReligionController@destroy']);
    
});
