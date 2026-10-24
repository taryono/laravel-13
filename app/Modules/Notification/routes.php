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
Route::group(['prefix' => 'notification', 'namespace' => 'App\Modules\Notification\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'notification.index', 'uses' => 'NotificationController@index']);
    Route::any('/search', ['as' => 'notification.search', 'uses' => 'NotificationController@search']);
    Route::get('/create', ['as' => 'notification.create', 'uses' => 'NotificationController@create']);
    Route::get('/show/{id}', ['as' => 'notification.show', 'uses' => 'NotificationController@show']);
    Route::get('/preview/{id}', ['as' => 'notification.preview', 'uses' => 'NotificationController@preview']);
    Route::get('/print/{id}', ['as' => 'notification.print', 'uses' => 'NotificationController@print']);

    Route::post('/store', ['as' => 'notification.store', 'uses' => 'NotificationController@store']);
    Route::get('/edit/{id}', ['as' => 'notification.edit', 'uses' => 'NotificationController@edit']);
    Route::put('/update/{id}', ['as' => 'notification.update', 'uses' => 'NotificationController@update']);
    Route::delete('/delete/{id}', ['as' => 'notification.destroy', 'uses' => 'NotificationController@destroy']);
    
});
