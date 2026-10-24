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
Route::group(['prefix' => 'digital-library-item', 'namespace' => 'App\Modules\DigitalLibraryItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'digital_library_item.index', 'uses' => 'DigitalLibraryItemController@index']);
    Route::any('/search', ['as' => 'digital_library_item.search', 'uses' => 'DigitalLibraryItemController@search']);
    Route::get('/create', ['as' => 'digital_library_item.create', 'uses' => 'DigitalLibraryItemController@create']);
    Route::get('/show/{id}', ['as' => 'digital_library_item.show', 'uses' => 'DigitalLibraryItemController@show']);
    Route::get('/preview/{id}', ['as' => 'digital_library_item.preview', 'uses' => 'DigitalLibraryItemController@preview']);
    Route::get('/print/{id}', ['as' => 'digital_library_item.print', 'uses' => 'DigitalLibraryItemController@print']);

    Route::post('/store', ['as' => 'digital_library_item.store', 'uses' => 'DigitalLibraryItemController@store']);
    Route::get('/edit/{id}', ['as' => 'digital_library_item.edit', 'uses' => 'DigitalLibraryItemController@edit']);
    Route::put('/update/{id}', ['as' => 'digital_library_item.update', 'uses' => 'DigitalLibraryItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'digital_library_item.destroy', 'uses' => 'DigitalLibraryItemController@destroy']);
    
});
