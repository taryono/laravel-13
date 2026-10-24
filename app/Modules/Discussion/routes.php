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
Route::group(['prefix' => 'discussion', 'namespace' => 'App\Modules\Discussion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'discussion.index', 'uses' => 'DiscussionController@index']);
    Route::any('/search', ['as' => 'discussion.search', 'uses' => 'DiscussionController@search']);
    Route::get('/create', ['as' => 'discussion.create', 'uses' => 'DiscussionController@create']);
    Route::get('/show/{id}', ['as' => 'discussion.show', 'uses' => 'DiscussionController@show']);
    Route::get('/preview/{id}', ['as' => 'discussion.preview', 'uses' => 'DiscussionController@preview']);
    Route::get('/print/{id}', ['as' => 'discussion.print', 'uses' => 'DiscussionController@print']);

    Route::post('/store', ['as' => 'discussion.store', 'uses' => 'DiscussionController@store']);
    Route::get('/edit/{id}', ['as' => 'discussion.edit', 'uses' => 'DiscussionController@edit']);
    Route::put('/update/{id}', ['as' => 'discussion.update', 'uses' => 'DiscussionController@update']);
    Route::delete('/delete/{id}', ['as' => 'discussion.destroy', 'uses' => 'DiscussionController@destroy']);
    
});
