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
Route::group(['prefix' => 'permission', 'namespace' => 'App\Modules\Permission\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'permission.index', 'uses' => 'PermissionController@index']);
    Route::any('/search', ['as' => 'permission.search', 'uses' => 'PermissionController@search']);
    Route::get('/create', ['as' => 'permission.create', 'uses' => 'PermissionController@create']);
    Route::get('/show/{permission}', ['as' => 'permission.show', 'uses' => 'PermissionController@show']);
    Route::get('/preview/{permission}', ['as' => 'permission.preview', 'uses' => 'PermissionController@preview']);
    Route::get('/print/{permission}', ['as' => 'permission.print', 'uses' => 'PermissionController@print']);

    Route::post('/store', ['as' => 'permission.store', 'uses' => 'PermissionController@store']);
    Route::get('/edit/{permission}', ['as' => 'permission.edit', 'uses' => 'PermissionController@edit']);
    Route::put('/update/{permission}', ['as' => 'permission.update', 'uses' => 'PermissionController@update']);
    Route::delete('/delete/{permission}', ['as' => 'permission.destroy', 'uses' => 'PermissionController@destroy']);
    
});
