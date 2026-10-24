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
Route::group(['prefix' => 'rkas-program', 'namespace' => 'App\Modules\RkasProgram\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_program.index', 'uses' => 'RkasProgramController@index']);
    Route::any('/search', ['as' => 'rkas_program.search', 'uses' => 'RkasProgramController@search']);
    Route::get('/create', ['as' => 'rkas_program.create', 'uses' => 'RkasProgramController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_program.show', 'uses' => 'RkasProgramController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_program.preview', 'uses' => 'RkasProgramController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_program.print', 'uses' => 'RkasProgramController@print']);

    Route::post('/store', ['as' => 'rkas_program.store', 'uses' => 'RkasProgramController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_program.edit', 'uses' => 'RkasProgramController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_program.update', 'uses' => 'RkasProgramController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_program.destroy', 'uses' => 'RkasProgramController@destroy']);
    
});
