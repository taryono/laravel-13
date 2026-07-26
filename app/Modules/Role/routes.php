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
Route::group(['prefix' => 'roles', 'namespace' => 'App\Modules\Approval\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'approval.index', 'uses' => 'RoleController@index']);
    Route::any('/search', ['as' => 'approval.search', 'uses' => 'RoleController@search']);
    Route::get('/create', ['as' => 'approval.create', 'uses' => 'RoleController@create']);
    Route::get('/show/{id}', ['as' => 'approval.show', 'uses' => 'RoleController@show']);
    Route::get('/preview/{id}', ['as' => 'approval.preview', 'uses' => 'RoleController@preview']);
    Route::get('/print/{id}', ['as' => 'approval.print', 'uses' => 'RoleController@print']);

    Route::post('/store', ['as' => 'approval.store', 'uses' => 'RoleController@store']);
    Route::get('/edit/{id}', ['as' => 'approval.edit', 'uses' => 'RoleController@edit']);
    Route::put('/update/{id}', ['as' => 'approval.update', 'uses' => 'RoleController@update']);
    Route::delete('/delete/{id}', ['as' => 'approval.destroy', 'uses' => 'RoleController@destroy']);
    Route::get('signaturepad', ['as' => 'approval.signaturepad', 'uses' => 'RoleController@signaturepad']);
    Route::post('signaturepad', ['as' => 'approval.upload', 'uses' => 'RoleController@upload']);
    Route::get('draw', ['as' => 'approval.draw', 'uses' => 'RoleController@draw']);
});
