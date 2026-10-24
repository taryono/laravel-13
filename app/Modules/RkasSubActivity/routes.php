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
Route::group(['prefix' => 'rkas-sub-activity', 'namespace' => 'App\Modules\RkasSubActivity\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_sub_activity.index', 'uses' => 'RkasSubActivityController@index']);
    Route::any('/search', ['as' => 'rkas_sub_activity.search', 'uses' => 'RkasSubActivityController@search']);
    Route::get('/create', ['as' => 'rkas_sub_activity.create', 'uses' => 'RkasSubActivityController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_sub_activity.show', 'uses' => 'RkasSubActivityController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_sub_activity.preview', 'uses' => 'RkasSubActivityController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_sub_activity.print', 'uses' => 'RkasSubActivityController@print']);

    Route::post('/store', ['as' => 'rkas_sub_activity.store', 'uses' => 'RkasSubActivityController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_sub_activity.edit', 'uses' => 'RkasSubActivityController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_sub_activity.update', 'uses' => 'RkasSubActivityController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_sub_activity.destroy', 'uses' => 'RkasSubActivityController@destroy']);
    
});
