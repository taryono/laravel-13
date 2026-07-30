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
Route::group(['prefix' => 'user-permission', 'namespace' => 'App\Modules\UserPermission\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'user-permission.index', 'uses' => 'UserPermissionController@index']);
    Route::any('/search', ['as' => 'user-permission.search', 'uses' => 'UserPermissionController@search']);
    Route::get('/create', ['as' => 'user-permission.create', 'uses' => 'UserPermissionController@create']);
    Route::get('/show/{user-permission}', ['as' => 'user-permission.show', 'uses' => 'UserPermissionController@show']);
    Route::get('/preview/{user-permission}', ['as' => 'user-permission.preview', 'uses' => 'UserPermissionController@preview']);
    Route::get('/print/{user-permission}', ['as' => 'user-permission.print', 'uses' => 'UserPermissionController@print']);

    Route::post('/store', ['as' => 'user-permission.store', 'uses' => 'UserPermissionController@store']);
    Route::get('/edit/{user-permission}', ['as' => 'user-permission.edit', 'uses' => 'UserPermissionController@edit']);
    Route::put('/update/{user-permission}', ['as' => 'user-permission.update', 'uses' => 'UserPermissionController@update']);
    Route::delete('/delete/{user-permission}', ['as' => 'user-permission.destroy', 'uses' => 'UserPermissionController@destroy']);
    
});
