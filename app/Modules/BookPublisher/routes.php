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
Route::group(['prefix' => 'book-publisher', 'namespace' => 'App\Modules\BookPublisher\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'book_publisher.index', 'uses' => 'BookPublisherController@index']);
    Route::any('/search', ['as' => 'book_publisher.search', 'uses' => 'BookPublisherController@search']);
    Route::get('/create', ['as' => 'book_publisher.create', 'uses' => 'BookPublisherController@create']);
    Route::get('/show/{id}', ['as' => 'book_publisher.show', 'uses' => 'BookPublisherController@show']);
    Route::get('/preview/{id}', ['as' => 'book_publisher.preview', 'uses' => 'BookPublisherController@preview']);
    Route::get('/print/{id}', ['as' => 'book_publisher.print', 'uses' => 'BookPublisherController@print']);

    Route::post('/store', ['as' => 'book_publisher.store', 'uses' => 'BookPublisherController@store']);
    Route::get('/edit/{id}', ['as' => 'book_publisher.edit', 'uses' => 'BookPublisherController@edit']);
    Route::put('/update/{id}', ['as' => 'book_publisher.update', 'uses' => 'BookPublisherController@update']);
    Route::delete('/delete/{id}', ['as' => 'book_publisher.destroy', 'uses' => 'BookPublisherController@destroy']);
    
});
