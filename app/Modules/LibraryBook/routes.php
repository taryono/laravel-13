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
Route::group(['prefix' => 'library-book', 'namespace' => 'App\Modules\LibraryBook\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'library_book.index', 'uses' => 'LibraryBookController@index']);
    Route::any('/search', ['as' => 'library_book.search', 'uses' => 'LibraryBookController@search']);
    Route::get('/create', ['as' => 'library_book.create', 'uses' => 'LibraryBookController@create']);
    Route::get('/show/{id}', ['as' => 'library_book.show', 'uses' => 'LibraryBookController@show']);
    Route::get('/preview/{id}', ['as' => 'library_book.preview', 'uses' => 'LibraryBookController@preview']);
    Route::get('/print/{id}', ['as' => 'library_book.print', 'uses' => 'LibraryBookController@print']);

    Route::post('/store', ['as' => 'library_book.store', 'uses' => 'LibraryBookController@store']);
    Route::get('/edit/{id}', ['as' => 'library_book.edit', 'uses' => 'LibraryBookController@edit']);
    Route::put('/update/{id}', ['as' => 'library_book.update', 'uses' => 'LibraryBookController@update']);
    Route::delete('/delete/{id}', ['as' => 'library_book.destroy', 'uses' => 'LibraryBookController@destroy']);
    
});
