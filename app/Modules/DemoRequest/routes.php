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
Route::group(['prefix' => 'demo-request', 'namespace' => 'App\Modules\DemoRequest\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'demo_request.index', 'uses' => 'DemoRequestController@index']);
    Route::any('/search', ['as' => 'demo_request.search', 'uses' => 'DemoRequestController@search']);
    Route::get('/create', ['as' => 'demo_request.create', 'uses' => 'DemoRequestController@create']);
    Route::get('/show/{id}', ['as' => 'demo_request.show', 'uses' => 'DemoRequestController@show']);
    Route::get('/preview/{id}', ['as' => 'demo_request.preview', 'uses' => 'DemoRequestController@preview']);
    Route::get('/print/{id}', ['as' => 'demo_request.print', 'uses' => 'DemoRequestController@print']);

    Route::post('/store', ['as' => 'demo_request.store', 'uses' => 'DemoRequestController@store']);
    Route::get('/edit/{id}', ['as' => 'demo_request.edit', 'uses' => 'DemoRequestController@edit']);
    Route::put('/update/{id}', ['as' => 'demo_request.update', 'uses' => 'DemoRequestController@update']);
    Route::delete('/delete/{id}', ['as' => 'demo_request.destroy', 'uses' => 'DemoRequestController@destroy']);
    
});
