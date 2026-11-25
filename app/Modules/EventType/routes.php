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
Route::group(['prefix' => 'event-type', 'namespace' => 'App\Modules\EventType\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'event_type.index', 'uses' => 'EventTypeController@index']);
    Route::any('/search', ['as' => 'event_type.search', 'uses' => 'EventTypeController@search']);
    Route::get('/create', ['as' => 'event_type.create', 'uses' => 'EventTypeController@create']);
    Route::get('/show/{id}', ['as' => 'event_type.show', 'uses' => 'EventTypeController@show']);
    Route::get('/preview/{id}', ['as' => 'event_type.preview', 'uses' => 'EventTypeController@preview']);
    Route::get('/print/{id}', ['as' => 'event_type.print', 'uses' => 'EventTypeController@print']);

    Route::post('/store', ['as' => 'event_type.store', 'uses' => 'EventTypeController@store']);
    Route::get('/edit/{id}', ['as' => 'event_type.edit', 'uses' => 'EventTypeController@edit']);
    Route::put('/update/{id}', ['as' => 'event_type.update', 'uses' => 'EventTypeController@update']);
    Route::delete('/delete/{id}', ['as' => 'event_type.destroy', 'uses' => 'EventTypeController@destroy']);
    
});
