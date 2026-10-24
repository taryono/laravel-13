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
Route::group(['prefix' => 'permission-request', 'namespace' => 'App\Modules\PermissionRequest\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'permission_request.index', 'uses' => 'PermissionRequestController@index']);
    Route::any('/search', ['as' => 'permission_request.search', 'uses' => 'PermissionRequestController@search']);
    Route::get('/create', ['as' => 'permission_request.create', 'uses' => 'PermissionRequestController@create']);
    Route::get('/show/{id}', ['as' => 'permission_request.show', 'uses' => 'PermissionRequestController@show']);
    Route::get('/preview/{id}', ['as' => 'permission_request.preview', 'uses' => 'PermissionRequestController@preview']);
    Route::get('/print/{id}', ['as' => 'permission_request.print', 'uses' => 'PermissionRequestController@print']);

    Route::post('/store', ['as' => 'permission_request.store', 'uses' => 'PermissionRequestController@store']);
    Route::get('/edit/{id}', ['as' => 'permission_request.edit', 'uses' => 'PermissionRequestController@edit']);
    Route::put('/update/{id}', ['as' => 'permission_request.update', 'uses' => 'PermissionRequestController@update']);
    Route::delete('/delete/{id}', ['as' => 'permission_request.destroy', 'uses' => 'PermissionRequestController@destroy']);
    
});
