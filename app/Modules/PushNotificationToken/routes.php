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
Route::group(['prefix' => 'push-notification-token', 'namespace' => 'App\Modules\PushNotificationToken\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'push_notification_token.index', 'uses' => 'PushNotificationTokenController@index']);
    Route::any('/search', ['as' => 'push_notification_token.search', 'uses' => 'PushNotificationTokenController@search']);
    Route::get('/create', ['as' => 'push_notification_token.create', 'uses' => 'PushNotificationTokenController@create']);
    Route::get('/show/{id}', ['as' => 'push_notification_token.show', 'uses' => 'PushNotificationTokenController@show']);
    Route::get('/preview/{id}', ['as' => 'push_notification_token.preview', 'uses' => 'PushNotificationTokenController@preview']);
    Route::get('/print/{id}', ['as' => 'push_notification_token.print', 'uses' => 'PushNotificationTokenController@print']);

    Route::post('/store', ['as' => 'push_notification_token.store', 'uses' => 'PushNotificationTokenController@store']);
    Route::get('/edit/{id}', ['as' => 'push_notification_token.edit', 'uses' => 'PushNotificationTokenController@edit']);
    Route::put('/update/{id}', ['as' => 'push_notification_token.update', 'uses' => 'PushNotificationTokenController@update']);
    Route::delete('/delete/{id}', ['as' => 'push_notification_token.destroy', 'uses' => 'PushNotificationTokenController@destroy']);
    
});
