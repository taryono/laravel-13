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
Route::group(['prefix' => 'discussion-reply', 'namespace' => 'App\Modules\DiscussionReply\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'discussion_reply.index', 'uses' => 'DiscussionReplyController@index']);
    Route::any('/search', ['as' => 'discussion_reply.search', 'uses' => 'DiscussionReplyController@search']);
    Route::get('/create', ['as' => 'discussion_reply.create', 'uses' => 'DiscussionReplyController@create']);
    Route::get('/show/{id}', ['as' => 'discussion_reply.show', 'uses' => 'DiscussionReplyController@show']);
    Route::get('/preview/{id}', ['as' => 'discussion_reply.preview', 'uses' => 'DiscussionReplyController@preview']);
    Route::get('/print/{id}', ['as' => 'discussion_reply.print', 'uses' => 'DiscussionReplyController@print']);

    Route::post('/store', ['as' => 'discussion_reply.store', 'uses' => 'DiscussionReplyController@store']);
    Route::get('/edit/{id}', ['as' => 'discussion_reply.edit', 'uses' => 'DiscussionReplyController@edit']);
    Route::put('/update/{id}', ['as' => 'discussion_reply.update', 'uses' => 'DiscussionReplyController@update']);
    Route::delete('/delete/{id}', ['as' => 'discussion_reply.destroy', 'uses' => 'DiscussionReplyController@destroy']);
    
});
