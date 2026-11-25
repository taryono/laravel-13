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
Route::group(['prefix' => 'sorogan-session', 'namespace' => 'App\Modules\SoroganSession\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sorogan_session.index', 'uses' => 'SoroganSessionController@index']);
    Route::any('/search', ['as' => 'sorogan_session.search', 'uses' => 'SoroganSessionController@search']);
    Route::get('/create', ['as' => 'sorogan_session.create', 'uses' => 'SoroganSessionController@create']);
    Route::get('/show/{id}', ['as' => 'sorogan_session.show', 'uses' => 'SoroganSessionController@show']);
    Route::get('/preview/{id}', ['as' => 'sorogan_session.preview', 'uses' => 'SoroganSessionController@preview']);
    Route::get('/print/{id}', ['as' => 'sorogan_session.print', 'uses' => 'SoroganSessionController@print']);

    Route::post('/store', ['as' => 'sorogan_session.store', 'uses' => 'SoroganSessionController@store']);
    Route::get('/edit/{id}', ['as' => 'sorogan_session.edit', 'uses' => 'SoroganSessionController@edit']);
    Route::put('/update/{id}', ['as' => 'sorogan_session.update', 'uses' => 'SoroganSessionController@update']);
    Route::delete('/delete/{id}', ['as' => 'sorogan_session.destroy', 'uses' => 'SoroganSessionController@destroy']);
    
});
