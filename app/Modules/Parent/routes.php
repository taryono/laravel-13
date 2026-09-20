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
Route::group(['prefix' => 'parent', 'namespace' => 'App\Modules\Parent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'parent.index', 'uses' => 'ParentController@index']);
    Route::any('/search', ['as' => 'parent.search', 'uses' => 'ParentController@search']);
    Route::get('/create', ['as' => 'parent.create', 'uses' => 'ParentController@create']);
    Route::get('/show/{id}', ['as' => 'parent.show', 'uses' => 'ParentController@show']);
    Route::get('/preview/{id}', ['as' => 'parent.preview', 'uses' => 'ParentController@preview']);
    Route::get('/print/{id}', ['as' => 'parent.print', 'uses' => 'ParentController@print']);

    Route::post('/store', ['as' => 'parent.store', 'uses' => 'ParentController@store']);
    Route::get('/edit/{id}', ['as' => 'parent.edit', 'uses' => 'ParentController@edit']);
    Route::put('/update/{id}', ['as' => 'parent.update', 'uses' => 'ParentController@update']);
    Route::delete('/delete/{id}', ['as' => 'parent.destroy', 'uses' => 'ParentController@destroy']);
    
});
