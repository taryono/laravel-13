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
Route::group(['prefix' => 'report-card-note', 'namespace' => 'App\Modules\ReportCardNote\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_card_note.index', 'uses' => 'ReportCardNoteController@index']);
    Route::any('/search', ['as' => 'report_card_note.search', 'uses' => 'ReportCardNoteController@search']);
    Route::get('/create', ['as' => 'report_card_note.create', 'uses' => 'ReportCardNoteController@create']);
    Route::get('/show/{id}', ['as' => 'report_card_note.show', 'uses' => 'ReportCardNoteController@show']);
    Route::get('/preview/{id}', ['as' => 'report_card_note.preview', 'uses' => 'ReportCardNoteController@preview']);
    Route::get('/print/{id}', ['as' => 'report_card_note.print', 'uses' => 'ReportCardNoteController@print']);

    Route::post('/store', ['as' => 'report_card_note.store', 'uses' => 'ReportCardNoteController@store']);
    Route::get('/edit/{id}', ['as' => 'report_card_note.edit', 'uses' => 'ReportCardNoteController@edit']);
    Route::put('/update/{id}', ['as' => 'report_card_note.update', 'uses' => 'ReportCardNoteController@update']);
    Route::delete('/delete/{id}', ['as' => 'report_card_note.destroy', 'uses' => 'ReportCardNoteController@destroy']);
    
});
