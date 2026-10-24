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
Route::group(['prefix' => 'notification-preference', 'namespace' => 'App\Modules\NotificationPreference\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'notification_preference.index', 'uses' => 'NotificationPreferenceController@index']);
    Route::any('/search', ['as' => 'notification_preference.search', 'uses' => 'NotificationPreferenceController@search']);
    Route::get('/create', ['as' => 'notification_preference.create', 'uses' => 'NotificationPreferenceController@create']);
    Route::get('/show/{id}', ['as' => 'notification_preference.show', 'uses' => 'NotificationPreferenceController@show']);
    Route::get('/preview/{id}', ['as' => 'notification_preference.preview', 'uses' => 'NotificationPreferenceController@preview']);
    Route::get('/print/{id}', ['as' => 'notification_preference.print', 'uses' => 'NotificationPreferenceController@print']);

    Route::post('/store', ['as' => 'notification_preference.store', 'uses' => 'NotificationPreferenceController@store']);
    Route::get('/edit/{id}', ['as' => 'notification_preference.edit', 'uses' => 'NotificationPreferenceController@edit']);
    Route::put('/update/{id}', ['as' => 'notification_preference.update', 'uses' => 'NotificationPreferenceController@update']);
    Route::delete('/delete/{id}', ['as' => 'notification_preference.destroy', 'uses' => 'NotificationPreferenceController@destroy']);
    
});
