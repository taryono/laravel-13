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
Route::group(['prefix' => 'email-log', 'namespace' => 'App\Modules\EmailLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'email_log.index', 'uses' => 'EmailLogController@index']);
    Route::any('/search', ['as' => 'email_log.search', 'uses' => 'EmailLogController@search']);
    Route::get('/create', ['as' => 'email_log.create', 'uses' => 'EmailLogController@create']);
    Route::get('/show/{id}', ['as' => 'email_log.show', 'uses' => 'EmailLogController@show']);
    Route::get('/preview/{id}', ['as' => 'email_log.preview', 'uses' => 'EmailLogController@preview']);
    Route::get('/print/{id}', ['as' => 'email_log.print', 'uses' => 'EmailLogController@print']);

    Route::post('/store', ['as' => 'email_log.store', 'uses' => 'EmailLogController@store']);
    Route::get('/edit/{id}', ['as' => 'email_log.edit', 'uses' => 'EmailLogController@edit']);
    Route::put('/update/{id}', ['as' => 'email_log.update', 'uses' => 'EmailLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'email_log.destroy', 'uses' => 'EmailLogController@destroy']);
    
});
