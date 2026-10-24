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
Route::group(['prefix' => 'applicant-achievement', 'namespace' => 'App\Modules\ApplicantAchievement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_achievement.index', 'uses' => 'ApplicantAchievementController@index']);
    Route::any('/search', ['as' => 'applicant_achievement.search', 'uses' => 'ApplicantAchievementController@search']);
    Route::get('/create', ['as' => 'applicant_achievement.create', 'uses' => 'ApplicantAchievementController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_achievement.show', 'uses' => 'ApplicantAchievementController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_achievement.preview', 'uses' => 'ApplicantAchievementController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_achievement.print', 'uses' => 'ApplicantAchievementController@print']);

    Route::post('/store', ['as' => 'applicant_achievement.store', 'uses' => 'ApplicantAchievementController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_achievement.edit', 'uses' => 'ApplicantAchievementController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_achievement.update', 'uses' => 'ApplicantAchievementController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_achievement.destroy', 'uses' => 'ApplicantAchievementController@destroy']);
    
});
