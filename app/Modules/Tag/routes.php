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
Route::group(['prefix' => 'tag', 'namespace' => 'App\Modules\Tag\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'tag.index', 'uses' => 'TagController@index']);
    Route::any('/search', ['as' => 'tag.search', 'uses' => 'TagController@search']);
    Route::get('/create', ['as' => 'tag.create', 'uses' => 'TagController@create']);
    Route::get('/show/{id}', ['as' => 'tag.show', 'uses' => 'TagController@show']);
    Route::get('/preview/{id}', ['as' => 'tag.preview', 'uses' => 'TagController@preview']);
    Route::get('/print/{id}', ['as' => 'tag.print', 'uses' => 'TagController@print']);

    Route::post('/store', ['as' => 'tag.store', 'uses' => 'TagController@store']);
    Route::get('/edit/{id}', ['as' => 'tag.edit', 'uses' => 'TagController@edit']);
    Route::put('/update/{id}', ['as' => 'tag.update', 'uses' => 'TagController@update']);
    Route::delete('/delete/{id}', ['as' => 'tag.destroy', 'uses' => 'TagController@destroy']);
    
});
