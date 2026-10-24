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
Route::group(['prefix' => 'gender', 'namespace' => 'App\Modules\Gender\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'gender.index', 'uses' => 'GenderController@index']);
    Route::any('/search', ['as' => 'gender.search', 'uses' => 'GenderController@search']);
    Route::get('/create', ['as' => 'gender.create', 'uses' => 'GenderController@create']);
    Route::get('/show/{id}', ['as' => 'gender.show', 'uses' => 'GenderController@show']);
    Route::get('/preview/{id}', ['as' => 'gender.preview', 'uses' => 'GenderController@preview']);
    Route::get('/print/{id}', ['as' => 'gender.print', 'uses' => 'GenderController@print']);

    Route::post('/store', ['as' => 'gender.store', 'uses' => 'GenderController@store']);
    Route::get('/edit/{id}', ['as' => 'gender.edit', 'uses' => 'GenderController@edit']);
    Route::put('/update/{id}', ['as' => 'gender.update', 'uses' => 'GenderController@update']);
    Route::delete('/delete/{id}', ['as' => 'gender.destroy', 'uses' => 'GenderController@destroy']);
    
});
