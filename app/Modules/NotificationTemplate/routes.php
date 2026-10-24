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
Route::group(['prefix' => 'notification-template', 'namespace' => 'App\Modules\NotificationTemplate\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'notification_template.index', 'uses' => 'NotificationTemplateController@index']);
    Route::any('/search', ['as' => 'notification_template.search', 'uses' => 'NotificationTemplateController@search']);
    Route::get('/create', ['as' => 'notification_template.create', 'uses' => 'NotificationTemplateController@create']);
    Route::get('/show/{id}', ['as' => 'notification_template.show', 'uses' => 'NotificationTemplateController@show']);
    Route::get('/preview/{id}', ['as' => 'notification_template.preview', 'uses' => 'NotificationTemplateController@preview']);
    Route::get('/print/{id}', ['as' => 'notification_template.print', 'uses' => 'NotificationTemplateController@print']);

    Route::post('/store', ['as' => 'notification_template.store', 'uses' => 'NotificationTemplateController@store']);
    Route::get('/edit/{id}', ['as' => 'notification_template.edit', 'uses' => 'NotificationTemplateController@edit']);
    Route::put('/update/{id}', ['as' => 'notification_template.update', 'uses' => 'NotificationTemplateController@update']);
    Route::delete('/delete/{id}', ['as' => 'notification_template.destroy', 'uses' => 'NotificationTemplateController@destroy']);
    
});
