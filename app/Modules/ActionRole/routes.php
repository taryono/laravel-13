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
Route::group(['prefix' => 'action-role', 'namespace' => 'App\Modules\ActionRole\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'action-role.index', 'uses' => 'ActionRoleController@index']);
    Route::any('/search', ['as' => 'action-role.search', 'uses' => 'ActionRoleController@search']);
    Route::get('/create', ['as' => 'action-role.create', 'uses' => 'ActionRoleController@create']);
    Route::get('/show/{action-role}', ['as' => 'action-role.show', 'uses' => 'ActionRoleController@show']);
    Route::get('/preview/{action-role}', ['as' => 'action-role.preview', 'uses' => 'ActionRoleController@preview']);
    Route::get('/print/{action-role}', ['as' => 'action-role.print', 'uses' => 'ActionRoleController@print']);

    Route::post('/store', ['as' => 'action-role.store', 'uses' => 'ActionRoleController@store']);
    Route::get('/edit/{action-role}', ['as' => 'action-role.edit', 'uses' => 'ActionRoleController@edit']);
    Route::put('/update/{action-role}', ['as' => 'action-role.update', 'uses' => 'ActionRoleController@update']);
    Route::delete('/delete/{action-role}', ['as' => 'action-role.destroy', 'uses' => 'ActionRoleController@destroy']);
    
});
