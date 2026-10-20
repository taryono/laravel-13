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
Route::group(['prefix' => 'student-rfid-card', 'namespace' => 'App\Modules\StudentRfidCard\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_rfid_card.index', 'uses' => 'StudentRfidCardController@index']);
    Route::any('/search', ['as' => 'student_rfid_card.search', 'uses' => 'StudentRfidCardController@search']);
    Route::get('/create', ['as' => 'student_rfid_card.create', 'uses' => 'StudentRfidCardController@create']);
    Route::get('/show/{id}', ['as' => 'student_rfid_card.show', 'uses' => 'StudentRfidCardController@show']);
    Route::get('/preview/{id}', ['as' => 'student_rfid_card.preview', 'uses' => 'StudentRfidCardController@preview']);
    Route::get('/print/{id}', ['as' => 'student_rfid_card.print', 'uses' => 'StudentRfidCardController@print']);

    Route::post('/store', ['as' => 'student_rfid_card.store', 'uses' => 'StudentRfidCardController@store']);
    Route::get('/edit/{id}', ['as' => 'student_rfid_card.edit', 'uses' => 'StudentRfidCardController@edit']);
    Route::put('/update/{id}', ['as' => 'student_rfid_card.update', 'uses' => 'StudentRfidCardController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_rfid_card.destroy', 'uses' => 'StudentRfidCardController@destroy']);
    
});
