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
Route::group(['prefix' => 'cms-post-revision', 'namespace' => 'App\Modules\CmsPostRevision\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_post_revision.index', 'uses' => 'CmsPostRevisionController@index']);
    Route::any('/search', ['as' => 'cms_post_revision.search', 'uses' => 'CmsPostRevisionController@search']);
    Route::get('/create', ['as' => 'cms_post_revision.create', 'uses' => 'CmsPostRevisionController@create']);
    Route::get('/show/{id}', ['as' => 'cms_post_revision.show', 'uses' => 'CmsPostRevisionController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_post_revision.preview', 'uses' => 'CmsPostRevisionController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_post_revision.print', 'uses' => 'CmsPostRevisionController@print']);

    Route::post('/store', ['as' => 'cms_post_revision.store', 'uses' => 'CmsPostRevisionController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_post_revision.edit', 'uses' => 'CmsPostRevisionController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_post_revision.update', 'uses' => 'CmsPostRevisionController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_post_revision.destroy', 'uses' => 'CmsPostRevisionController@destroy']);
    
});
