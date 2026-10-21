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
Route::group(['prefix' => 'cms-post', 'namespace' => 'App\Modules\CmsPost\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_post.index', 'uses' => 'CmsPostController@index']);
    Route::any('/search', ['as' => 'cms_post.search', 'uses' => 'CmsPostController@search']);
    Route::get('/create', ['as' => 'cms_post.create', 'uses' => 'CmsPostController@create']);
    Route::get('/show/{id}', ['as' => 'cms_post.show', 'uses' => 'CmsPostController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_post.preview', 'uses' => 'CmsPostController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_post.print', 'uses' => 'CmsPostController@print']);

    Route::post('/store', ['as' => 'cms_post.store', 'uses' => 'CmsPostController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_post.edit', 'uses' => 'CmsPostController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_post.update', 'uses' => 'CmsPostController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_post.destroy', 'uses' => 'CmsPostController@destroy']);
    
});
