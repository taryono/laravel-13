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
Route::group(['prefix' => 'payroll-notification', 'namespace' => 'App\Modules\PayrollNotification\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'payroll_notification.index', 'uses' => 'PayrollNotificationController@index']);
    Route::any('/search', ['as' => 'payroll_notification.search', 'uses' => 'PayrollNotificationController@search']);
    Route::get('/create', ['as' => 'payroll_notification.create', 'uses' => 'PayrollNotificationController@create']);
    Route::get('/show/{id}', ['as' => 'payroll_notification.show', 'uses' => 'PayrollNotificationController@show']);
    Route::get('/preview/{id}', ['as' => 'payroll_notification.preview', 'uses' => 'PayrollNotificationController@preview']);
    Route::get('/print/{id}', ['as' => 'payroll_notification.print', 'uses' => 'PayrollNotificationController@print']);

    Route::post('/store', ['as' => 'payroll_notification.store', 'uses' => 'PayrollNotificationController@store']);
    Route::get('/edit/{id}', ['as' => 'payroll_notification.edit', 'uses' => 'PayrollNotificationController@edit']);
    Route::put('/update/{id}', ['as' => 'payroll_notification.update', 'uses' => 'PayrollNotificationController@update']);
    Route::delete('/delete/{id}', ['as' => 'payroll_notification.destroy', 'uses' => 'PayrollNotificationController@destroy']);
    
});
