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
Route::group(['prefix' => 'payment-notification', 'namespace' => 'App\Modules\PaymentNotification\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payment_notification.index', 'uses' => 'PaymentNotificationController@index']);
    Route::any('/search', ['as' => 'payment_notification.search', 'uses' => 'PaymentNotificationController@search']);
    Route::get('/create', ['as' => 'payment_notification.create', 'uses' => 'PaymentNotificationController@create']);
    Route::get('/show/{id}', ['as' => 'payment_notification.show', 'uses' => 'PaymentNotificationController@show']);
    Route::get('/preview/{id}', ['as' => 'payment_notification.preview', 'uses' => 'PaymentNotificationController@preview']);
    Route::get('/print/{id}', ['as' => 'payment_notification.print', 'uses' => 'PaymentNotificationController@print']);

    Route::post('/store', ['as' => 'payment_notification.store', 'uses' => 'PaymentNotificationController@store']);
    Route::get('/edit/{id}', ['as' => 'payment_notification.edit', 'uses' => 'PaymentNotificationController@edit']);
    Route::put('/update/{id}', ['as' => 'payment_notification.update', 'uses' => 'PaymentNotificationController@update']);
    Route::delete('/delete/{id}', ['as' => 'payment_notification.destroy', 'uses' => 'PaymentNotificationController@destroy']);
    
});
