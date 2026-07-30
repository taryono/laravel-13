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
Route::group(['prefix' => 'module', 'namespace' => 'App\Modules\Module\Controllers', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', ['as' => 'module.index', 'uses' => 'ModuleController@index']);
    Route::any('/search', ['as' => 'module.search', 'uses' => 'ModuleController@search']);
    Route::get('/create', ['as' => 'module.create', 'uses' => 'ModuleController@create']);
    Route::get('/show/{id}', ['as' => 'module.show', 'uses' => 'ModuleController@show']);
    Route::get('/preview/{id}', ['as' => 'module.preview', 'uses' => 'ModuleController@preview']);
    Route::get('/print/{id}', ['as' => 'module.print', 'uses' => 'ModuleController@print']);

    Route::post('/store', ['as' => 'module.store', 'uses' => 'ModuleController@store']);
    Route::get('/edit/{id}', ['as' => 'module.edit', 'uses' => 'ModuleController@edit']);
    Route::put('/update/{id}', ['as' => 'module.update', 'uses' => 'ModuleController@update']);
    Route::delete('/delete/{id}', ['as' => 'module.destroy', 'uses' => 'ModuleController@destroy']);
    
});
