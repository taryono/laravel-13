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
Route::group(['prefix' => 'cms-tag', 'namespace' => 'App\Modules\CmsTag\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_tag.index', 'uses' => 'CmsTagController@index']);
    Route::any('/search', ['as' => 'cms_tag.search', 'uses' => 'CmsTagController@search']);
    Route::get('/create', ['as' => 'cms_tag.create', 'uses' => 'CmsTagController@create']);
    Route::get('/show/{id}', ['as' => 'cms_tag.show', 'uses' => 'CmsTagController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_tag.preview', 'uses' => 'CmsTagController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_tag.print', 'uses' => 'CmsTagController@print']);

    Route::post('/store', ['as' => 'cms_tag.store', 'uses' => 'CmsTagController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_tag.edit', 'uses' => 'CmsTagController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_tag.update', 'uses' => 'CmsTagController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_tag.destroy', 'uses' => 'CmsTagController@destroy']);
    
});
