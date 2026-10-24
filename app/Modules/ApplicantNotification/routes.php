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
Route::group(['prefix' => 'applicant-notification', 'namespace' => 'App\Modules\ApplicantNotification\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_notification.index', 'uses' => 'ApplicantNotificationController@index']);
    Route::any('/search', ['as' => 'applicant_notification.search', 'uses' => 'ApplicantNotificationController@search']);
    Route::get('/create', ['as' => 'applicant_notification.create', 'uses' => 'ApplicantNotificationController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_notification.show', 'uses' => 'ApplicantNotificationController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_notification.preview', 'uses' => 'ApplicantNotificationController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_notification.print', 'uses' => 'ApplicantNotificationController@print']);

    Route::post('/store', ['as' => 'applicant_notification.store', 'uses' => 'ApplicantNotificationController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_notification.edit', 'uses' => 'ApplicantNotificationController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_notification.update', 'uses' => 'ApplicantNotificationController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_notification.destroy', 'uses' => 'ApplicantNotificationController@destroy']);
    
});
