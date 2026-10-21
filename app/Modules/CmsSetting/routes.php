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
Route::group(['prefix' => 'cms-setting', 'namespace' => 'App\Modules\CmsSetting\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_setting.index', 'uses' => 'CmsSettingController@index']);
    Route::any('/search', ['as' => 'cms_setting.search', 'uses' => 'CmsSettingController@search']);
    Route::get('/create', ['as' => 'cms_setting.create', 'uses' => 'CmsSettingController@create']);
    Route::get('/show/{id}', ['as' => 'cms_setting.show', 'uses' => 'CmsSettingController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_setting.preview', 'uses' => 'CmsSettingController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_setting.print', 'uses' => 'CmsSettingController@print']);

    Route::post('/store', ['as' => 'cms_setting.store', 'uses' => 'CmsSettingController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_setting.edit', 'uses' => 'CmsSettingController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_setting.update', 'uses' => 'CmsSettingController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_setting.destroy', 'uses' => 'CmsSettingController@destroy']);
    
});
