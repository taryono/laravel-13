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
Route::group(['prefix' => 'book-category', 'namespace' => 'App\Modules\BookCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'book_category.index', 'uses' => 'BookCategoryController@index']);
    Route::any('/search', ['as' => 'book_category.search', 'uses' => 'BookCategoryController@search']);
    Route::get('/create', ['as' => 'book_category.create', 'uses' => 'BookCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'book_category.show', 'uses' => 'BookCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'book_category.preview', 'uses' => 'BookCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'book_category.print', 'uses' => 'BookCategoryController@print']);

    Route::post('/store', ['as' => 'book_category.store', 'uses' => 'BookCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'book_category.edit', 'uses' => 'BookCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'book_category.update', 'uses' => 'BookCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'book_category.destroy', 'uses' => 'BookCategoryController@destroy']);
    
});
