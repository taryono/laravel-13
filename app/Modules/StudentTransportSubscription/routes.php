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
Route::group(['prefix' => 'student-transport-subscription', 'namespace' => 'App\Modules\StudentTransportSubscription\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_transport_subscription.index', 'uses' => 'StudentTransportSubscriptionController@index']);
    Route::any('/search', ['as' => 'student_transport_subscription.search', 'uses' => 'StudentTransportSubscriptionController@search']);
    Route::get('/create', ['as' => 'student_transport_subscription.create', 'uses' => 'StudentTransportSubscriptionController@create']);
    Route::get('/show/{id}', ['as' => 'student_transport_subscription.show', 'uses' => 'StudentTransportSubscriptionController@show']);
    Route::get('/preview/{id}', ['as' => 'student_transport_subscription.preview', 'uses' => 'StudentTransportSubscriptionController@preview']);
    Route::get('/print/{id}', ['as' => 'student_transport_subscription.print', 'uses' => 'StudentTransportSubscriptionController@print']);

    Route::post('/store', ['as' => 'student_transport_subscription.store', 'uses' => 'StudentTransportSubscriptionController@store']);
    Route::get('/edit/{id}', ['as' => 'student_transport_subscription.edit', 'uses' => 'StudentTransportSubscriptionController@edit']);
    Route::put('/update/{id}', ['as' => 'student_transport_subscription.update', 'uses' => 'StudentTransportSubscriptionController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_transport_subscription.destroy', 'uses' => 'StudentTransportSubscriptionController@destroy']);
    
});
