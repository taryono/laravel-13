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
Route::group(['prefix' => 'difficulty-level', 'namespace' => 'App\Modules\DifficultyLevel\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'difficulty_level.index', 'uses' => 'DifficultyLevelController@index']);
    Route::any('/search', ['as' => 'difficulty_level.search', 'uses' => 'DifficultyLevelController@search']);
    Route::get('/create', ['as' => 'difficulty_level.create', 'uses' => 'DifficultyLevelController@create']);
    Route::get('/show/{id}', ['as' => 'difficulty_level.show', 'uses' => 'DifficultyLevelController@show']);
    Route::get('/preview/{id}', ['as' => 'difficulty_level.preview', 'uses' => 'DifficultyLevelController@preview']);
    Route::get('/print/{id}', ['as' => 'difficulty_level.print', 'uses' => 'DifficultyLevelController@print']);

    Route::post('/store', ['as' => 'difficulty_level.store', 'uses' => 'DifficultyLevelController@store']);
    Route::get('/edit/{id}', ['as' => 'difficulty_level.edit', 'uses' => 'DifficultyLevelController@edit']);
    Route::put('/update/{id}', ['as' => 'difficulty_level.update', 'uses' => 'DifficultyLevelController@update']);
    Route::delete('/delete/{id}', ['as' => 'difficulty_level.destroy', 'uses' => 'DifficultyLevelController@destroy']);
    
});
