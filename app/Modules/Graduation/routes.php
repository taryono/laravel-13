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
Route::group(['prefix' => 'graduation', 'namespace' => 'App\Modules\Graduation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'graduation.index', 'uses' => 'GraduationController@index']);
    Route::any('/search', ['as' => 'graduation.search', 'uses' => 'GraduationController@search']);
    Route::get('/create', ['as' => 'graduation.create', 'uses' => 'GraduationController@create']);
    Route::get('/show/{id}', ['as' => 'graduation.show', 'uses' => 'GraduationController@show']);
    Route::get('/preview/{id}', ['as' => 'graduation.preview', 'uses' => 'GraduationController@preview']);
    Route::get('/print/{id}', ['as' => 'graduation.print', 'uses' => 'GraduationController@print']);

    Route::post('/store', ['as' => 'graduation.store', 'uses' => 'GraduationController@store']);
    Route::get('/edit/{id}', ['as' => 'graduation.edit', 'uses' => 'GraduationController@edit']);
    Route::put('/update/{id}', ['as' => 'graduation.update', 'uses' => 'GraduationController@update']);
    Route::delete('/delete/{id}', ['as' => 'graduation.destroy', 'uses' => 'GraduationController@destroy']);
    
});
