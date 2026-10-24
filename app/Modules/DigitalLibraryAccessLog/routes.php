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
Route::group(['prefix' => 'digital-library-access-log', 'namespace' => 'App\Modules\DigitalLibraryAccessLog\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'digital_library_access_log.index', 'uses' => 'DigitalLibraryAccessLogController@index']);
    Route::any('/search', ['as' => 'digital_library_access_log.search', 'uses' => 'DigitalLibraryAccessLogController@search']);
    Route::get('/create', ['as' => 'digital_library_access_log.create', 'uses' => 'DigitalLibraryAccessLogController@create']);
    Route::get('/show/{id}', ['as' => 'digital_library_access_log.show', 'uses' => 'DigitalLibraryAccessLogController@show']);
    Route::get('/preview/{id}', ['as' => 'digital_library_access_log.preview', 'uses' => 'DigitalLibraryAccessLogController@preview']);
    Route::get('/print/{id}', ['as' => 'digital_library_access_log.print', 'uses' => 'DigitalLibraryAccessLogController@print']);

    Route::post('/store', ['as' => 'digital_library_access_log.store', 'uses' => 'DigitalLibraryAccessLogController@store']);
    Route::get('/edit/{id}', ['as' => 'digital_library_access_log.edit', 'uses' => 'DigitalLibraryAccessLogController@edit']);
    Route::put('/update/{id}', ['as' => 'digital_library_access_log.update', 'uses' => 'DigitalLibraryAccessLogController@update']);
    Route::delete('/delete/{id}', ['as' => 'digital_library_access_log.destroy', 'uses' => 'DigitalLibraryAccessLogController@destroy']);
    
});
