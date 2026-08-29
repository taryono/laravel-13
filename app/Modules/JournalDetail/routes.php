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
Route::group(['prefix' => 'journal-detail', 'namespace' => 'App\Modules\JournalDetail\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'journal_detail.index', 'uses' => 'JournalDetailController@index']);
    Route::any('/search', ['as' => 'journal_detail.search', 'uses' => 'JournalDetailController@search']);
    Route::get('/create', ['as' => 'journal_detail.create', 'uses' => 'JournalDetailController@create']);
    Route::get('/show/{id}', ['as' => 'journal_detail.show', 'uses' => 'JournalDetailController@show']);
    Route::get('/preview/{id}', ['as' => 'journal_detail.preview', 'uses' => 'JournalDetailController@preview']);
    Route::get('/print/{id}', ['as' => 'journal_detail.print', 'uses' => 'JournalDetailController@print']);

    Route::post('/store', ['as' => 'journal_detail.store', 'uses' => 'JournalDetailController@store']);
    Route::get('/edit/{id}', ['as' => 'journal_detail.edit', 'uses' => 'JournalDetailController@edit']);
    Route::put('/update/{id}', ['as' => 'journal_detail.update', 'uses' => 'JournalDetailController@update']);
    Route::delete('/delete/{id}', ['as' => 'journal_detail.destroy', 'uses' => 'JournalDetailController@destroy']);
    
});
