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
Route::group(['prefix' => 'cognitive-level', 'namespace' => 'App\Modules\CognitiveLevel\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cognitive_level.index', 'uses' => 'CognitiveLevelController@index']);
    Route::any('/search', ['as' => 'cognitive_level.search', 'uses' => 'CognitiveLevelController@search']);
    Route::get('/create', ['as' => 'cognitive_level.create', 'uses' => 'CognitiveLevelController@create']);
    Route::get('/show/{id}', ['as' => 'cognitive_level.show', 'uses' => 'CognitiveLevelController@show']);
    Route::get('/preview/{id}', ['as' => 'cognitive_level.preview', 'uses' => 'CognitiveLevelController@preview']);
    Route::get('/print/{id}', ['as' => 'cognitive_level.print', 'uses' => 'CognitiveLevelController@print']);

    Route::post('/store', ['as' => 'cognitive_level.store', 'uses' => 'CognitiveLevelController@store']);
    Route::get('/edit/{id}', ['as' => 'cognitive_level.edit', 'uses' => 'CognitiveLevelController@edit']);
    Route::put('/update/{id}', ['as' => 'cognitive_level.update', 'uses' => 'CognitiveLevelController@update']);
    Route::delete('/delete/{id}', ['as' => 'cognitive_level.destroy', 'uses' => 'CognitiveLevelController@destroy']);
    
});
