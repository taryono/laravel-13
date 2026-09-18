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
Route::group(['prefix' => 'announcement', 'namespace' => 'App\Modules\Announcement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'announcement.index', 'uses' => 'AnnouncementController@index']);
    Route::any('/search', ['as' => 'announcement.search', 'uses' => 'AnnouncementController@search']);
    Route::get('/create', ['as' => 'announcement.create', 'uses' => 'AnnouncementController@create']);
    Route::get('/show/{id}', ['as' => 'announcement.show', 'uses' => 'AnnouncementController@show']);
    Route::get('/preview/{id}', ['as' => 'announcement.preview', 'uses' => 'AnnouncementController@preview']);
    Route::get('/print/{id}', ['as' => 'announcement.print', 'uses' => 'AnnouncementController@print']);

    Route::post('/store', ['as' => 'announcement.store', 'uses' => 'AnnouncementController@store']);
    Route::get('/edit/{id}', ['as' => 'announcement.edit', 'uses' => 'AnnouncementController@edit']);
    Route::put('/update/{id}', ['as' => 'announcement.update', 'uses' => 'AnnouncementController@update']);
    Route::delete('/delete/{id}', ['as' => 'announcement.destroy', 'uses' => 'AnnouncementController@destroy']);
    
});
