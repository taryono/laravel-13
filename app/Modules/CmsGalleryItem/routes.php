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
Route::group(['prefix' => 'cms-gallery-item', 'namespace' => 'App\Modules\CmsGalleryItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_gallery_item.index', 'uses' => 'CmsGalleryItemController@index']);
    Route::any('/search', ['as' => 'cms_gallery_item.search', 'uses' => 'CmsGalleryItemController@search']);
    Route::get('/create', ['as' => 'cms_gallery_item.create', 'uses' => 'CmsGalleryItemController@create']);
    Route::get('/show/{id}', ['as' => 'cms_gallery_item.show', 'uses' => 'CmsGalleryItemController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_gallery_item.preview', 'uses' => 'CmsGalleryItemController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_gallery_item.print', 'uses' => 'CmsGalleryItemController@print']);

    Route::post('/store', ['as' => 'cms_gallery_item.store', 'uses' => 'CmsGalleryItemController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_gallery_item.edit', 'uses' => 'CmsGalleryItemController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_gallery_item.update', 'uses' => 'CmsGalleryItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_gallery_item.destroy', 'uses' => 'CmsGalleryItemController@destroy']);
    
});
