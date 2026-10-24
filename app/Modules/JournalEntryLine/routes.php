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
Route::group(['prefix' => 'journal-entry-line', 'namespace' => 'App\Modules\JournalEntryLine\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'journal_entry_line.index', 'uses' => 'JournalEntryLineController@index']);
    Route::any('/search', ['as' => 'journal_entry_line.search', 'uses' => 'JournalEntryLineController@search']);
    Route::get('/create', ['as' => 'journal_entry_line.create', 'uses' => 'JournalEntryLineController@create']);
    Route::get('/show/{id}', ['as' => 'journal_entry_line.show', 'uses' => 'JournalEntryLineController@show']);
    Route::get('/preview/{id}', ['as' => 'journal_entry_line.preview', 'uses' => 'JournalEntryLineController@preview']);
    Route::get('/print/{id}', ['as' => 'journal_entry_line.print', 'uses' => 'JournalEntryLineController@print']);

    Route::post('/store', ['as' => 'journal_entry_line.store', 'uses' => 'JournalEntryLineController@store']);
    Route::get('/edit/{id}', ['as' => 'journal_entry_line.edit', 'uses' => 'JournalEntryLineController@edit']);
    Route::put('/update/{id}', ['as' => 'journal_entry_line.update', 'uses' => 'JournalEntryLineController@update']);
    Route::delete('/delete/{id}', ['as' => 'journal_entry_line.destroy', 'uses' => 'JournalEntryLineController@destroy']);
    
});
