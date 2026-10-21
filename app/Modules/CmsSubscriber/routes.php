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
Route::group(['prefix' => 'cms-subscriber', 'namespace' => 'App\Modules\CmsSubscriber\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cms_subscriber.index', 'uses' => 'CmsSubscriberController@index']);
    Route::any('/search', ['as' => 'cms_subscriber.search', 'uses' => 'CmsSubscriberController@search']);
    Route::get('/create', ['as' => 'cms_subscriber.create', 'uses' => 'CmsSubscriberController@create']);
    Route::get('/show/{id}', ['as' => 'cms_subscriber.show', 'uses' => 'CmsSubscriberController@show']);
    Route::get('/preview/{id}', ['as' => 'cms_subscriber.preview', 'uses' => 'CmsSubscriberController@preview']);
    Route::get('/print/{id}', ['as' => 'cms_subscriber.print', 'uses' => 'CmsSubscriberController@print']);

    Route::post('/store', ['as' => 'cms_subscriber.store', 'uses' => 'CmsSubscriberController@store']);
    Route::get('/edit/{id}', ['as' => 'cms_subscriber.edit', 'uses' => 'CmsSubscriberController@edit']);
    Route::put('/update/{id}', ['as' => 'cms_subscriber.update', 'uses' => 'CmsSubscriberController@update']);
    Route::delete('/delete/{id}', ['as' => 'cms_subscriber.destroy', 'uses' => 'CmsSubscriberController@destroy']);
    
});
