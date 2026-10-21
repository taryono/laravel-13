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
Route::group(['prefix' => 'cms-banner', 'namespace' => 'App\Modules\CmsBanner\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_banner.index', 'uses' => 'CmsBannerController@index']);
    Route::any('/search', ['as' => 'cms_banner.search', 'uses' => 'CmsBannerController@search']);
    Route::get('/create', ['as' => 'cms_banner.create', 'uses' => 'CmsBannerController@create']);
    Route::get('/show/{id}', ['as' => 'cms_banner.show', 'uses' => 'CmsBannerController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_banner.preview', 'uses' => 'CmsBannerController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_banner.print', 'uses' => 'CmsBannerController@print']);

    Route::post('/store', ['as' => 'cms_banner.store', 'uses' => 'CmsBannerController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_banner.edit', 'uses' => 'CmsBannerController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_banner.update', 'uses' => 'CmsBannerController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_banner.destroy', 'uses' => 'CmsBannerController@destroy']);
    
});
