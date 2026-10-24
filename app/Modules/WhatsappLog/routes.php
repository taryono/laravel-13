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
Route::group(['prefix' => 'whatsapp-log', 'namespace' => 'App\Modules\WhatsappLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'whatsapp_log.index', 'uses' => 'WhatsappLogController@index']);
    Route::any('/search', ['as' => 'whatsapp_log.search', 'uses' => 'WhatsappLogController@search']);
    Route::get('/create', ['as' => 'whatsapp_log.create', 'uses' => 'WhatsappLogController@create']);
    Route::get('/show/{id}', ['as' => 'whatsapp_log.show', 'uses' => 'WhatsappLogController@show']);
    Route::get('/preview/{id}', ['as' => 'whatsapp_log.preview', 'uses' => 'WhatsappLogController@preview']);
    Route::get('/print/{id}', ['as' => 'whatsapp_log.print', 'uses' => 'WhatsappLogController@print']);

    Route::post('/store', ['as' => 'whatsapp_log.store', 'uses' => 'WhatsappLogController@store']);
    Route::get('/edit/{id}', ['as' => 'whatsapp_log.edit', 'uses' => 'WhatsappLogController@edit']);
    Route::put('/update/{id}', ['as' => 'whatsapp_log.update', 'uses' => 'WhatsappLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'whatsapp_log.destroy', 'uses' => 'WhatsappLogController@destroy']);
    
});
