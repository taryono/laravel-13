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
Route::group(['prefix' => 'rkas-activity', 'namespace' => 'App\Modules\RkasActivity\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_activity.index', 'uses' => 'RkasActivityController@index']);
    Route::any('/search', ['as' => 'rkas_activity.search', 'uses' => 'RkasActivityController@search']);
    Route::get('/create', ['as' => 'rkas_activity.create', 'uses' => 'RkasActivityController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_activity.show', 'uses' => 'RkasActivityController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_activity.preview', 'uses' => 'RkasActivityController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_activity.print', 'uses' => 'RkasActivityController@print']);

    Route::post('/store', ['as' => 'rkas_activity.store', 'uses' => 'RkasActivityController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_activity.edit', 'uses' => 'RkasActivityController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_activity.update', 'uses' => 'RkasActivityController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_activity.destroy', 'uses' => 'RkasActivityController@destroy']);
    
});
