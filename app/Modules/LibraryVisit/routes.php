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
Route::group(['prefix' => 'library-visit', 'namespace' => 'App\Modules\LibraryVisit\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'library_visit.index', 'uses' => 'LibraryVisitController@index']);
    Route::any('/search', ['as' => 'library_visit.search', 'uses' => 'LibraryVisitController@search']);
    Route::get('/create', ['as' => 'library_visit.create', 'uses' => 'LibraryVisitController@create']);
    Route::get('/show/{id}', ['as' => 'library_visit.show', 'uses' => 'LibraryVisitController@show']);
    Route::get('/preview/{id}', ['as' => 'library_visit.preview', 'uses' => 'LibraryVisitController@preview']);
    Route::get('/print/{id}', ['as' => 'library_visit.print', 'uses' => 'LibraryVisitController@print']);

    Route::post('/store', ['as' => 'library_visit.store', 'uses' => 'LibraryVisitController@store']);
    Route::get('/edit/{id}', ['as' => 'library_visit.edit', 'uses' => 'LibraryVisitController@edit']);
    Route::put('/update/{id}', ['as' => 'library_visit.update', 'uses' => 'LibraryVisitController@update']);
    Route::delete('/delete/{id}', ['as' => 'library_visit.destroy', 'uses' => 'LibraryVisitController@destroy']);
    
});
