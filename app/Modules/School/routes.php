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
    Route::get('/show/{school}', ['as' => 'school.show', 'uses' => 'SchoolController@show']);
    Route::get('/preview/{school}', ['as' => 'school.preview', 'uses' => 'SchoolController@preview']);
    Route::get('/print/{school}', ['as' => 'school.print', 'uses' => 'SchoolController@print']);

    Route::post('/store', ['as' => 'school.store', 'uses' => 'SchoolController@store']);
    Route::get('/edit/{school}', ['as' => 'school.edit', 'uses' => 'SchoolController@edit']);
    Route::put('/update/{school}', ['as' => 'school.update', 'uses' => 'SchoolController@update']);
    Route::delete('/delete/{school}', ['as' => 'school.destroy', 'uses' => 'SchoolController@destroy']);
    
});
