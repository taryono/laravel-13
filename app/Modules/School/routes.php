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
Route::group(['prefix' => 'school', 'namespace' => 'App\Modules\School\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'school.index', 'uses' => 'SchoolController@index']);
    Route::any('/search', ['as' => 'school.search', 'uses' => 'SchoolController@search']);
    Route::get('/create', ['as' => 'school.create', 'uses' => 'SchoolController@create']);
    Route::get('/show/{id}', ['as' => 'school.show', 'uses' => 'SchoolController@show']);
    Route::get('/preview/{id}', ['as' => 'school.preview', 'uses' => 'SchoolController@preview']);
    Route::get('/print/{id}', ['as' => 'school.print', 'uses' => 'SchoolController@print']);

    Route::post('/store', ['as' => 'school.store', 'uses' => 'SchoolController@store']);
    Route::get('/edit/{id}', ['as' => 'school.edit', 'uses' => 'SchoolController@edit']);
    Route::put('/update/{id}', ['as' => 'school.update', 'uses' => 'SchoolController@update']);
    Route::delete('/delete/{id}', ['as' => 'school.destroy', 'uses' => 'SchoolController@destroy']);
    
});
