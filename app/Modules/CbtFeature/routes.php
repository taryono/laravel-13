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
Route::group(['prefix' => 'cbt-feature', 'namespace' => 'App\Modules\CbtFeature\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_feature.index', 'uses' => 'CbtFeatureController@index']);
    Route::any('/search', ['as' => 'cbt_feature.search', 'uses' => 'CbtFeatureController@search']);
    Route::get('/create', ['as' => 'cbt_feature.create', 'uses' => 'CbtFeatureController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_feature.show', 'uses' => 'CbtFeatureController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_feature.preview', 'uses' => 'CbtFeatureController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_feature.print', 'uses' => 'CbtFeatureController@print']);

    Route::post('/store', ['as' => 'cbt_feature.store', 'uses' => 'CbtFeatureController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_feature.edit', 'uses' => 'CbtFeatureController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_feature.update', 'uses' => 'CbtFeatureController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_feature.destroy', 'uses' => 'CbtFeatureController@destroy']);
    
});
