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
Route::group(['prefix' => 'book-author', 'namespace' => 'App\Modules\BookAuthor\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'book_author.index', 'uses' => 'BookAuthorController@index']);
    Route::any('/search', ['as' => 'book_author.search', 'uses' => 'BookAuthorController@search']);
    Route::get('/create', ['as' => 'book_author.create', 'uses' => 'BookAuthorController@create']);
    Route::get('/show/{id}', ['as' => 'book_author.show', 'uses' => 'BookAuthorController@show']);
    Route::get('/preview/{id}', ['as' => 'book_author.preview', 'uses' => 'BookAuthorController@preview']);
    Route::get('/print/{id}', ['as' => 'book_author.print', 'uses' => 'BookAuthorController@print']);

    Route::post('/store', ['as' => 'book_author.store', 'uses' => 'BookAuthorController@store']);
    Route::get('/edit/{id}', ['as' => 'book_author.edit', 'uses' => 'BookAuthorController@edit']);
    Route::put('/update/{id}', ['as' => 'book_author.update', 'uses' => 'BookAuthorController@update']);
    Route::delete('/delete/{id}', ['as' => 'book_author.destroy', 'uses' => 'BookAuthorController@destroy']);
    
});
