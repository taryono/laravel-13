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
Route::group(['prefix' => 'cbt-schedule-feature', 'namespace' => 'App\Modules\CbtScheduleFeature\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_schedule_feature.index', 'uses' => 'CbtScheduleFeatureController@index']);
    Route::any('/search', ['as' => 'cbt_schedule_feature.search', 'uses' => 'CbtScheduleFeatureController@search']);
    Route::get('/create', ['as' => 'cbt_schedule_feature.create', 'uses' => 'CbtScheduleFeatureController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_schedule_feature.show', 'uses' => 'CbtScheduleFeatureController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_schedule_feature.preview', 'uses' => 'CbtScheduleFeatureController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_schedule_feature.print', 'uses' => 'CbtScheduleFeatureController@print']);

    Route::post('/store', ['as' => 'cbt_schedule_feature.store', 'uses' => 'CbtScheduleFeatureController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_schedule_feature.edit', 'uses' => 'CbtScheduleFeatureController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_schedule_feature.update', 'uses' => 'CbtScheduleFeatureController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_schedule_feature.destroy', 'uses' => 'CbtScheduleFeatureController@destroy']);
    
});
