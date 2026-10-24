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
Route::group(['prefix' => 'counseling-session', 'namespace' => 'App\Modules\CounselingSession\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'counseling_session.index', 'uses' => 'CounselingSessionController@index']);
    Route::any('/search', ['as' => 'counseling_session.search', 'uses' => 'CounselingSessionController@search']);
    Route::get('/create', ['as' => 'counseling_session.create', 'uses' => 'CounselingSessionController@create']);
    Route::get('/show/{id}', ['as' => 'counseling_session.show', 'uses' => 'CounselingSessionController@show']);
    Route::get('/preview/{id}', ['as' => 'counseling_session.preview', 'uses' => 'CounselingSessionController@preview']);
    Route::get('/print/{id}', ['as' => 'counseling_session.print', 'uses' => 'CounselingSessionController@print']);

    Route::post('/store', ['as' => 'counseling_session.store', 'uses' => 'CounselingSessionController@store']);
    Route::get('/edit/{id}', ['as' => 'counseling_session.edit', 'uses' => 'CounselingSessionController@edit']);
    Route::put('/update/{id}', ['as' => 'counseling_session.update', 'uses' => 'CounselingSessionController@update']);
    Route::delete('/delete/{id}', ['as' => 'counseling_session.destroy', 'uses' => 'CounselingSessionController@destroy']);
    
});
