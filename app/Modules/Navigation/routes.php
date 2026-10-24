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
Route::group(['prefix' => 'navigation', 'namespace' => 'App\Modules\Navigation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'navigation.index', 'uses' => 'NavigationController@index']);
    Route::any('/search', ['as' => 'navigation.search', 'uses' => 'NavigationController@search']);
    Route::get('/create', ['as' => 'navigation.create', 'uses' => 'NavigationController@create']);
    Route::get('/show/{id}', ['as' => 'navigation.show', 'uses' => 'NavigationController@show']);
    Route::get('/preview/{id}', ['as' => 'navigation.preview', 'uses' => 'NavigationController@preview']);
    Route::get('/print/{id}', ['as' => 'navigation.print', 'uses' => 'NavigationController@print']);

    Route::post('/store', ['as' => 'navigation.store', 'uses' => 'NavigationController@store']);
    Route::get('/edit/{id}', ['as' => 'navigation.edit', 'uses' => 'NavigationController@edit']);
    Route::put('/update/{id}', ['as' => 'navigation.update', 'uses' => 'NavigationController@update']);
    Route::delete('/delete/{id}', ['as' => 'navigation.destroy', 'uses' => 'NavigationController@destroy']);
    
});
