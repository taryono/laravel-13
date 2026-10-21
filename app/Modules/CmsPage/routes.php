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
Route::group(['prefix' => 'cms-page', 'namespace' => 'App\Modules\CmsPage\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_page.index', 'uses' => 'CmsPageController@index']);
    Route::any('/search', ['as' => 'cms_page.search', 'uses' => 'CmsPageController@search']);
    Route::get('/create', ['as' => 'cms_page.create', 'uses' => 'CmsPageController@create']);
    Route::get('/show/{id}', ['as' => 'cms_page.show', 'uses' => 'CmsPageController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_page.preview', 'uses' => 'CmsPageController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_page.print', 'uses' => 'CmsPageController@print']);

    Route::post('/store', ['as' => 'cms_page.store', 'uses' => 'CmsPageController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_page.edit', 'uses' => 'CmsPageController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_page.update', 'uses' => 'CmsPageController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_page.destroy', 'uses' => 'CmsPageController@destroy']);
    
});
