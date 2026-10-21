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
Route::group(['prefix' => 'cms-media', 'namespace' => 'App\Modules\CmsMedia\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_media.index', 'uses' => 'CmsMediaController@index']);
    Route::any('/search', ['as' => 'cms_media.search', 'uses' => 'CmsMediaController@search']);
    Route::get('/create', ['as' => 'cms_media.create', 'uses' => 'CmsMediaController@create']);
    Route::get('/show/{id}', ['as' => 'cms_media.show', 'uses' => 'CmsMediaController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_media.preview', 'uses' => 'CmsMediaController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_media.print', 'uses' => 'CmsMediaController@print']);

    Route::post('/store', ['as' => 'cms_media.store', 'uses' => 'CmsMediaController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_media.edit', 'uses' => 'CmsMediaController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_media.update', 'uses' => 'CmsMediaController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_media.destroy', 'uses' => 'CmsMediaController@destroy']);
    
});
