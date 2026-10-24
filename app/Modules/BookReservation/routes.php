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
Route::group(['prefix' => 'book-reservation', 'namespace' => 'App\Modules\BookReservation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'book_reservation.index', 'uses' => 'BookReservationController@index']);
    Route::any('/search', ['as' => 'book_reservation.search', 'uses' => 'BookReservationController@search']);
    Route::get('/create', ['as' => 'book_reservation.create', 'uses' => 'BookReservationController@create']);
    Route::get('/show/{id}', ['as' => 'book_reservation.show', 'uses' => 'BookReservationController@show']);
    Route::get('/preview/{id}', ['as' => 'book_reservation.preview', 'uses' => 'BookReservationController@preview']);
    Route::get('/print/{id}', ['as' => 'book_reservation.print', 'uses' => 'BookReservationController@print']);

    Route::post('/store', ['as' => 'book_reservation.store', 'uses' => 'BookReservationController@store']);
    Route::get('/edit/{id}', ['as' => 'book_reservation.edit', 'uses' => 'BookReservationController@edit']);
    Route::put('/update/{id}', ['as' => 'book_reservation.update', 'uses' => 'BookReservationController@update']);
    Route::delete('/delete/{id}', ['as' => 'book_reservation.destroy', 'uses' => 'BookReservationController@destroy']);
    
});
