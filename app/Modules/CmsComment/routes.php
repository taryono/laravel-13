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
Route::group(['prefix' => 'cms-comment', 'namespace' => 'App\Modules\CmsComment\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_comment.index', 'uses' => 'CmsCommentController@index']);
    Route::any('/search', ['as' => 'cms_comment.search', 'uses' => 'CmsCommentController@search']);
    Route::get('/create', ['as' => 'cms_comment.create', 'uses' => 'CmsCommentController@create']);
    Route::get('/show/{id}', ['as' => 'cms_comment.show', 'uses' => 'CmsCommentController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_comment.preview', 'uses' => 'CmsCommentController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_comment.print', 'uses' => 'CmsCommentController@print']);

    Route::post('/store', ['as' => 'cms_comment.store', 'uses' => 'CmsCommentController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_comment.edit', 'uses' => 'CmsCommentController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_comment.update', 'uses' => 'CmsCommentController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_comment.destroy', 'uses' => 'CmsCommentController@destroy']);
    
});
