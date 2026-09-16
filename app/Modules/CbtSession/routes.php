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
Route::group(['prefix' => 'cbt-session', 'namespace' => 'App\Modules\CbtSession\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_session.index', 'uses' => 'CbtSessionController@index']);
    Route::any('/search', ['as' => 'cbt_session.search', 'uses' => 'CbtSessionController@search']);
    Route::get('/create', ['as' => 'cbt_session.create', 'uses' => 'CbtSessionController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_session.show', 'uses' => 'CbtSessionController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_session.preview', 'uses' => 'CbtSessionController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_session.print', 'uses' => 'CbtSessionController@print']);

    Route::post('/store', ['as' => 'cbt_session.store', 'uses' => 'CbtSessionController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_session.edit', 'uses' => 'CbtSessionController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_session.update', 'uses' => 'CbtSessionController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_session.destroy', 'uses' => 'CbtSessionController@destroy']);
    
});
