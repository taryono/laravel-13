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
Route::group(['prefix' => 'class-level', 'namespace' => 'App\Modules\ClassLevel\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'class_level.index', 'uses' => 'ClassLevelController@index']);
    Route::any('/search', ['as' => 'class_level.search', 'uses' => 'ClassLevelController@search']);
    Route::get('/create', ['as' => 'class_level.create', 'uses' => 'ClassLevelController@create']);
    Route::get('/show/{id}', ['as' => 'class_level.show', 'uses' => 'ClassLevelController@show']);
    Route::get('/preview/{id}', ['as' => 'class_level.preview', 'uses' => 'ClassLevelController@preview']);
    Route::get('/print/{id}', ['as' => 'class_level.print', 'uses' => 'ClassLevelController@print']);

    Route::post('/store', ['as' => 'class_level.store', 'uses' => 'ClassLevelController@store']);
    Route::get('/edit/{id}', ['as' => 'class_level.edit', 'uses' => 'ClassLevelController@edit']);
    Route::put('/update/{id}', ['as' => 'class_level.update', 'uses' => 'ClassLevelController@update']);
    Route::delete('/delete/{id}', ['as' => 'class_level.destroy', 'uses' => 'ClassLevelController@destroy']);
    
});
