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
Route::group(['prefix' => 'cms-category', 'namespace' => 'App\Modules\CmsCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_category.index', 'uses' => 'CmsCategoryController@index']);
    Route::any('/search', ['as' => 'cms_category.search', 'uses' => 'CmsCategoryController@search']);
    Route::get('/create', ['as' => 'cms_category.create', 'uses' => 'CmsCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'cms_category.show', 'uses' => 'CmsCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_category.preview', 'uses' => 'CmsCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_category.print', 'uses' => 'CmsCategoryController@print']);

    Route::post('/store', ['as' => 'cms_category.store', 'uses' => 'CmsCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_category.edit', 'uses' => 'CmsCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_category.update', 'uses' => 'CmsCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_category.destroy', 'uses' => 'CmsCategoryController@destroy']);
    
});
