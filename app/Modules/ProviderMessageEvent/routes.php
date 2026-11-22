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
Route::group(['prefix' => 'provider-message-event', 'namespace' => 'App\Modules\ProviderMessageEvent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'provider_message_event.index', 'uses' => 'ProviderMessageEventController@index']);
    Route::any('/search', ['as' => 'provider_message_event.search', 'uses' => 'ProviderMessageEventController@search']);
    Route::get('/create', ['as' => 'provider_message_event.create', 'uses' => 'ProviderMessageEventController@create']);
    Route::get('/show/{id}', ['as' => 'provider_message_event.show', 'uses' => 'ProviderMessageEventController@show']);
    Route::get('/preview/{id}', ['as' => 'provider_message_event.preview', 'uses' => 'ProviderMessageEventController@preview']);
    Route::get('/print/{id}', ['as' => 'provider_message_event.print', 'uses' => 'ProviderMessageEventController@print']);

    Route::post('/store', ['as' => 'provider_message_event.store', 'uses' => 'ProviderMessageEventController@store']);
    Route::get('/edit/{id}', ['as' => 'provider_message_event.edit', 'uses' => 'ProviderMessageEventController@edit']);
    Route::put('/update/{id}', ['as' => 'provider_message_event.update', 'uses' => 'ProviderMessageEventController@update']);
    Route::delete('/delete/{id}', ['as' => 'provider_message_event.destroy', 'uses' => 'ProviderMessageEventController@destroy']);
    
});
