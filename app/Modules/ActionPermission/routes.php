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
Route::group(['prefix' => 'action-permission', 'namespace' => 'App\Modules\ActionPermission\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'action-permission.index', 'uses' => 'ActionPermissionController@index']);
    Route::any('/search', ['as' => 'action-permission.search', 'uses' => 'ActionPermissionController@search']);
    Route::get('/create', ['as' => 'action-permission.create', 'uses' => 'ActionPermissionController@create']);
    Route::get('/show/{action-permission}', ['as' => 'action-permission.show', 'uses' => 'ActionPermissionController@show']);
    Route::get('/preview/{action-permission}', ['as' => 'action-permission.preview', 'uses' => 'ActionPermissionController@preview']);
    Route::get('/print/{action-permission}', ['as' => 'action-permission.print', 'uses' => 'ActionPermissionController@print']);

    Route::post('/store', ['as' => 'action-permission.store', 'uses' => 'ActionPermissionController@store']);
    Route::get('/edit/{action-permission}', ['as' => 'action-permission.edit', 'uses' => 'ActionPermissionController@edit']);
    Route::put('/update/{action-permission}', ['as' => 'action-permission.update', 'uses' => 'ActionPermissionController@update']);
    Route::delete('/delete/{action-permission}', ['as' => 'action-permission.destroy', 'uses' => 'ActionPermissionController@destroy']);
    
});
