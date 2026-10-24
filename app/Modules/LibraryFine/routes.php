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
Route::group(['prefix' => 'library-fine', 'namespace' => 'App\Modules\LibraryFine\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'library_fine.index', 'uses' => 'LibraryFineController@index']);
    Route::any('/search', ['as' => 'library_fine.search', 'uses' => 'LibraryFineController@search']);
    Route::get('/create', ['as' => 'library_fine.create', 'uses' => 'LibraryFineController@create']);
    Route::get('/show/{id}', ['as' => 'library_fine.show', 'uses' => 'LibraryFineController@show']);
    Route::get('/preview/{id}', ['as' => 'library_fine.preview', 'uses' => 'LibraryFineController@preview']);
    Route::get('/print/{id}', ['as' => 'library_fine.print', 'uses' => 'LibraryFineController@print']);

    Route::post('/store', ['as' => 'library_fine.store', 'uses' => 'LibraryFineController@store']);
    Route::get('/edit/{id}', ['as' => 'library_fine.edit', 'uses' => 'LibraryFineController@edit']);
    Route::put('/update/{id}', ['as' => 'library_fine.update', 'uses' => 'LibraryFineController@update']);
    Route::delete('/delete/{id}', ['as' => 'library_fine.destroy', 'uses' => 'LibraryFineController@destroy']);
    
});
