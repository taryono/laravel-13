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
Route::group(['prefix' => 'cms-gallery', 'namespace' => 'App\Modules\CmsGallery\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_gallery.index', 'uses' => 'CmsGalleryController@index']);
    Route::any('/search', ['as' => 'cms_gallery.search', 'uses' => 'CmsGalleryController@search']);
    Route::get('/create', ['as' => 'cms_gallery.create', 'uses' => 'CmsGalleryController@create']);
    Route::get('/show/{id}', ['as' => 'cms_gallery.show', 'uses' => 'CmsGalleryController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_gallery.preview', 'uses' => 'CmsGalleryController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_gallery.print', 'uses' => 'CmsGalleryController@print']);

    Route::post('/store', ['as' => 'cms_gallery.store', 'uses' => 'CmsGalleryController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_gallery.edit', 'uses' => 'CmsGalleryController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_gallery.update', 'uses' => 'CmsGalleryController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_gallery.destroy', 'uses' => 'CmsGalleryController@destroy']);
    
});
