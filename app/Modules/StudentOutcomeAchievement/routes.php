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
Route::group(['prefix' => 'student-outcome-achievement', 'namespace' => 'App\Modules\StudentOutcomeAchievement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_outcome_achievement.index', 'uses' => 'StudentOutcomeAchievementController@index']);
    Route::any('/search', ['as' => 'student_outcome_achievement.search', 'uses' => 'StudentOutcomeAchievementController@search']);
    Route::get('/create', ['as' => 'student_outcome_achievement.create', 'uses' => 'StudentOutcomeAchievementController@create']);
    Route::get('/show/{id}', ['as' => 'student_outcome_achievement.show', 'uses' => 'StudentOutcomeAchievementController@show']);
    Route::get('/preview/{id}', ['as' => 'student_outcome_achievement.preview', 'uses' => 'StudentOutcomeAchievementController@preview']);
    Route::get('/print/{id}', ['as' => 'student_outcome_achievement.print', 'uses' => 'StudentOutcomeAchievementController@print']);

    Route::post('/store', ['as' => 'student_outcome_achievement.store', 'uses' => 'StudentOutcomeAchievementController@store']);
    Route::get('/edit/{id}', ['as' => 'student_outcome_achievement.edit', 'uses' => 'StudentOutcomeAchievementController@edit']);
    Route::put('/update/{id}', ['as' => 'student_outcome_achievement.update', 'uses' => 'StudentOutcomeAchievementController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_outcome_achievement.destroy', 'uses' => 'StudentOutcomeAchievementController@destroy']);
    
});
