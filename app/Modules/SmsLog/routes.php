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
Route::group(['prefix' => 'sms-log', 'namespace' => 'App\Modules\SmsLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sms_log.index', 'uses' => 'SmsLogController@index']);
    Route::any('/search', ['as' => 'sms_log.search', 'uses' => 'SmsLogController@search']);
    Route::get('/create', ['as' => 'sms_log.create', 'uses' => 'SmsLogController@create']);
    Route::get('/show/{id}', ['as' => 'sms_log.show', 'uses' => 'SmsLogController@show']);
    Route::get('/preview/{id}', ['as' => 'sms_log.preview', 'uses' => 'SmsLogController@preview']);
    Route::get('/print/{id}', ['as' => 'sms_log.print', 'uses' => 'SmsLogController@print']);

    Route::post('/store', ['as' => 'sms_log.store', 'uses' => 'SmsLogController@store']);
    Route::get('/edit/{id}', ['as' => 'sms_log.edit', 'uses' => 'SmsLogController@edit']);
    Route::put('/update/{id}', ['as' => 'sms_log.update', 'uses' => 'SmsLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'sms_log.destroy', 'uses' => 'SmsLogController@destroy']);
    
});
