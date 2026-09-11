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
Route::group(['prefix' => 'achievement', 'namespace' => 'App\Modules\Achievement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'achievement.index', 'uses' => 'AchievementController@index']);
    Route::any('/search', ['as' => 'achievement.search', 'uses' => 'AchievementController@search']);
    Route::get('/create', ['as' => 'achievement.create', 'uses' => 'AchievementController@create']);
    Route::get('/show/{id}', ['as' => 'achievement.show', 'uses' => 'AchievementController@show']);
    Route::get('/preview/{id}', ['as' => 'achievement.preview', 'uses' => 'AchievementController@preview']);
    Route::get('/print/{id}', ['as' => 'achievement.print', 'uses' => 'AchievementController@print']);

    Route::post('/store', ['as' => 'achievement.store', 'uses' => 'AchievementController@store']);
    Route::get('/edit/{id}', ['as' => 'achievement.edit', 'uses' => 'AchievementController@edit']);
    Route::put('/update/{id}', ['as' => 'achievement.update', 'uses' => 'AchievementController@update']);
    Route::delete('/delete/{id}', ['as' => 'achievement.destroy', 'uses' => 'AchievementController@destroy']);
    
});
