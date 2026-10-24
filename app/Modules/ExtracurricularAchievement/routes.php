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
Route::group(['prefix' => 'extracurricular-achievement', 'namespace' => 'App\Modules\ExtracurricularAchievement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'extracurricular_achievement.index', 'uses' => 'ExtracurricularAchievementController@index']);
    Route::any('/search', ['as' => 'extracurricular_achievement.search', 'uses' => 'ExtracurricularAchievementController@search']);
    Route::get('/create', ['as' => 'extracurricular_achievement.create', 'uses' => 'ExtracurricularAchievementController@create']);
    Route::get('/show/{id}', ['as' => 'extracurricular_achievement.show', 'uses' => 'ExtracurricularAchievementController@show']);
    Route::get('/preview/{id}', ['as' => 'extracurricular_achievement.preview', 'uses' => 'ExtracurricularAchievementController@preview']);
    Route::get('/print/{id}', ['as' => 'extracurricular_achievement.print', 'uses' => 'ExtracurricularAchievementController@print']);

    Route::post('/store', ['as' => 'extracurricular_achievement.store', 'uses' => 'ExtracurricularAchievementController@store']);
    Route::get('/edit/{id}', ['as' => 'extracurricular_achievement.edit', 'uses' => 'ExtracurricularAchievementController@edit']);
    Route::put('/update/{id}', ['as' => 'extracurricular_achievement.update', 'uses' => 'ExtracurricularAchievementController@update']);
    Route::delete('/delete/{id}', ['as' => 'extracurricular_achievement.destroy', 'uses' => 'ExtracurricularAchievementController@destroy']);
    
});
