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
Route::group(['prefix' => 'approval-history', 'namespace' => 'App\Modules\ApprovalHistory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'approval_history.index', 'uses' => 'ApprovalHistoryController@index']);
    Route::any('/search', ['as' => 'approval_history.search', 'uses' => 'ApprovalHistoryController@search']);
    Route::get('/create', ['as' => 'approval_history.create', 'uses' => 'ApprovalHistoryController@create']);
    Route::get('/show/{id}', ['as' => 'approval_history.show', 'uses' => 'ApprovalHistoryController@show']);
    Route::get('/preview/{id}', ['as' => 'approval_history.preview', 'uses' => 'ApprovalHistoryController@preview']);
    Route::get('/print/{id}', ['as' => 'approval_history.print', 'uses' => 'ApprovalHistoryController@print']);

    Route::post('/store', ['as' => 'approval_history.store', 'uses' => 'ApprovalHistoryController@store']);
    Route::get('/edit/{id}', ['as' => 'approval_history.edit', 'uses' => 'ApprovalHistoryController@edit']);
    Route::put('/update/{id}', ['as' => 'approval_history.update', 'uses' => 'ApprovalHistoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'approval_history.destroy', 'uses' => 'ApprovalHistoryController@destroy']);
    
});
