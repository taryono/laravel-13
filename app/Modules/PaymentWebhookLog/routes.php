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
Route::group(['prefix' => 'payment-webhook-log', 'namespace' => 'App\Modules\PaymentWebhookLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payment_webhook_log.index', 'uses' => 'PaymentWebhookLogController@index']);
    Route::any('/search', ['as' => 'payment_webhook_log.search', 'uses' => 'PaymentWebhookLogController@search']);
    Route::get('/create', ['as' => 'payment_webhook_log.create', 'uses' => 'PaymentWebhookLogController@create']);
    Route::get('/show/{id}', ['as' => 'payment_webhook_log.show', 'uses' => 'PaymentWebhookLogController@show']);
    Route::get('/preview/{id}', ['as' => 'payment_webhook_log.preview', 'uses' => 'PaymentWebhookLogController@preview']);
    Route::get('/print/{id}', ['as' => 'payment_webhook_log.print', 'uses' => 'PaymentWebhookLogController@print']);

    Route::post('/store', ['as' => 'payment_webhook_log.store', 'uses' => 'PaymentWebhookLogController@store']);
    Route::get('/edit/{id}', ['as' => 'payment_webhook_log.edit', 'uses' => 'PaymentWebhookLogController@edit']);
    Route::put('/update/{id}', ['as' => 'payment_webhook_log.update', 'uses' => 'PaymentWebhookLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'payment_webhook_log.destroy', 'uses' => 'PaymentWebhookLogController@destroy']);
    
});
