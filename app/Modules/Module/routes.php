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
Route::group(['prefix' => 'module', 'namespace' => 'App\Modules\Module\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'module.index', 'uses' => 'ModuleController@index']);
    Route::any('/search', ['as' => 'module.search', 'uses' => 'ModuleController@search']);
    Route::get('/create', ['as' => 'module.create', 'uses' => 'ModuleController@create']);
    Route::get('/show/{module}', ['as' => 'module.show', 'uses' => 'ModuleController@show']);
    Route::get('/preview/{module}', ['as' => 'module.preview', 'uses' => 'ModuleController@preview']);
    Route::get('/print/{module}', ['as' => 'module.print', 'uses' => 'ModuleController@print']);

    Route::post('/store', ['as' => 'module.store', 'uses' => 'ModuleController@store']);
    Route::get('/edit/{module}', ['as' => 'module.edit', 'uses' => 'ModuleController@edit']);
    Route::put('/update/{module}', ['as' => 'module.update', 'uses' => 'ModuleController@update']);
    Route::delete('/delete/{module}', ['as' => 'module.destroy', 'uses' => 'ModuleController@destroy']);
    
});
