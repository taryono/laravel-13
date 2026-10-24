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
Route::group(['prefix' => 'book-copy', 'namespace' => 'App\Modules\BookCopy\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'book_copy.index', 'uses' => 'BookCopyController@index']);
    Route::any('/search', ['as' => 'book_copy.search', 'uses' => 'BookCopyController@search']);
    Route::get('/create', ['as' => 'book_copy.create', 'uses' => 'BookCopyController@create']);
    Route::get('/show/{id}', ['as' => 'book_copy.show', 'uses' => 'BookCopyController@show']);
    Route::get('/preview/{id}', ['as' => 'book_copy.preview', 'uses' => 'BookCopyController@preview']);
    Route::get('/print/{id}', ['as' => 'book_copy.print', 'uses' => 'BookCopyController@print']);

    Route::post('/store', ['as' => 'book_copy.store', 'uses' => 'BookCopyController@store']);
    Route::get('/edit/{id}', ['as' => 'book_copy.edit', 'uses' => 'BookCopyController@edit']);
    Route::put('/update/{id}', ['as' => 'book_copy.update', 'uses' => 'BookCopyController@update']);
    Route::delete('/delete/{id}', ['as' => 'book_copy.destroy', 'uses' => 'BookCopyController@destroy']);
    
});
