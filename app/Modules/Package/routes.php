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
Route::group(['prefix' => 'package', 'namespace' => 'App\Modules\Package\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'package.index', 'uses' => 'PackageController@index']);
    Route::any('/search', ['as' => 'package.search', 'uses' => 'PackageController@search']);
    Route::get('/create', ['as' => 'package.create', 'uses' => 'PackageController@create']);
    Route::get('/show/{id}', ['as' => 'package.show', 'uses' => 'PackageController@show']);
    Route::get('/preview/{id}', ['as' => 'package.preview', 'uses' => 'PackageController@preview']);
    Route::get('/print/{id}', ['as' => 'package.print', 'uses' => 'PackageController@print']);

    Route::post('/store', ['as' => 'package.store', 'uses' => 'PackageController@store']);
    Route::get('/edit/{id}', ['as' => 'package.edit', 'uses' => 'PackageController@edit']);
    Route::put('/update/{id}', ['as' => 'package.update', 'uses' => 'PackageController@update']);
    Route::delete('/delete/{id}', ['as' => 'package.destroy', 'uses' => 'PackageController@destroy']);
    
});
