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
Route::group(['prefix' => 'journal-entry', 'namespace' => 'App\Modules\JournalEntry\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'journal_entry.index', 'uses' => 'JournalEntryController@index']);
    Route::any('/search', ['as' => 'journal_entry.search', 'uses' => 'JournalEntryController@search']);
    Route::get('/create', ['as' => 'journal_entry.create', 'uses' => 'JournalEntryController@create']);
    Route::get('/show/{id}', ['as' => 'journal_entry.show', 'uses' => 'JournalEntryController@show']);
    Route::get('/preview/{id}', ['as' => 'journal_entry.preview', 'uses' => 'JournalEntryController@preview']);
    Route::get('/print/{id}', ['as' => 'journal_entry.print', 'uses' => 'JournalEntryController@print']);

    Route::post('/store', ['as' => 'journal_entry.store', 'uses' => 'JournalEntryController@store']);
    Route::get('/edit/{id}', ['as' => 'journal_entry.edit', 'uses' => 'JournalEntryController@edit']);
    Route::put('/update/{id}', ['as' => 'journal_entry.update', 'uses' => 'JournalEntryController@update']);
    Route::delete('/delete/{id}', ['as' => 'journal_entry.destroy', 'uses' => 'JournalEntryController@destroy']);
    
});
