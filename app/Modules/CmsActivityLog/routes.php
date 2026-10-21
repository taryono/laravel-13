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
Route::group(['prefix' => 'cms-activity-log', 'namespace' => 'App\Modules\CmsActivityLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_activity_log.index', 'uses' => 'CmsActivityLogController@index']);
    Route::any('/search', ['as' => 'cms_activity_log.search', 'uses' => 'CmsActivityLogController@search']);
    Route::get('/create', ['as' => 'cms_activity_log.create', 'uses' => 'CmsActivityLogController@create']);
    Route::get('/show/{id}', ['as' => 'cms_activity_log.show', 'uses' => 'CmsActivityLogController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_activity_log.preview', 'uses' => 'CmsActivityLogController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_activity_log.print', 'uses' => 'CmsActivityLogController@print']);

    Route::post('/store', ['as' => 'cms_activity_log.store', 'uses' => 'CmsActivityLogController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_activity_log.edit', 'uses' => 'CmsActivityLogController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_activity_log.update', 'uses' => 'CmsActivityLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_activity_log.destroy', 'uses' => 'CmsActivityLogController@destroy']);
    
});
