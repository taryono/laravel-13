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
Route::group(['prefix' => 'rkas-revision', 'namespace' => 'App\Modules\RkasRevision\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_revision.index', 'uses' => 'RkasRevisionController@index']);
    Route::any('/search', ['as' => 'rkas_revision.search', 'uses' => 'RkasRevisionController@search']);
    Route::get('/create', ['as' => 'rkas_revision.create', 'uses' => 'RkasRevisionController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_revision.show', 'uses' => 'RkasRevisionController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_revision.preview', 'uses' => 'RkasRevisionController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_revision.print', 'uses' => 'RkasRevisionController@print']);

    Route::post('/store', ['as' => 'rkas_revision.store', 'uses' => 'RkasRevisionController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_revision.edit', 'uses' => 'RkasRevisionController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_revision.update', 'uses' => 'RkasRevisionController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_revision.destroy', 'uses' => 'RkasRevisionController@destroy']);
    
});
