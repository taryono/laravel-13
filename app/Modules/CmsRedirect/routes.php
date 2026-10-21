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
Route::group(['prefix' => 'cms-redirect', 'namespace' => 'App\Modules\CmsRedirect\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_redirect.index', 'uses' => 'CmsRedirectController@index']);
    Route::any('/search', ['as' => 'cms_redirect.search', 'uses' => 'CmsRedirectController@search']);
    Route::get('/create', ['as' => 'cms_redirect.create', 'uses' => 'CmsRedirectController@create']);
    Route::get('/show/{id}', ['as' => 'cms_redirect.show', 'uses' => 'CmsRedirectController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_redirect.preview', 'uses' => 'CmsRedirectController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_redirect.print', 'uses' => 'CmsRedirectController@print']);

    Route::post('/store', ['as' => 'cms_redirect.store', 'uses' => 'CmsRedirectController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_redirect.edit', 'uses' => 'CmsRedirectController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_redirect.update', 'uses' => 'CmsRedirectController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_redirect.destroy', 'uses' => 'CmsRedirectController@destroy']);
    
});
