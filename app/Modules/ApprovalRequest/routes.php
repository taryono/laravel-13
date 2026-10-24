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
Route::group(['prefix' => 'approval-request', 'namespace' => 'App\Modules\ApprovalRequest\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'approval_request.index', 'uses' => 'ApprovalRequestController@index']);
    Route::any('/search', ['as' => 'approval_request.search', 'uses' => 'ApprovalRequestController@search']);
    Route::get('/create', ['as' => 'approval_request.create', 'uses' => 'ApprovalRequestController@create']);
    Route::get('/show/{id}', ['as' => 'approval_request.show', 'uses' => 'ApprovalRequestController@show']);
    Route::get('/preview/{id}', ['as' => 'approval_request.preview', 'uses' => 'ApprovalRequestController@preview']);
    Route::get('/print/{id}', ['as' => 'approval_request.print', 'uses' => 'ApprovalRequestController@print']);

    Route::post('/store', ['as' => 'approval_request.store', 'uses' => 'ApprovalRequestController@store']);
    Route::get('/edit/{id}', ['as' => 'approval_request.edit', 'uses' => 'ApprovalRequestController@edit']);
    Route::put('/update/{id}', ['as' => 'approval_request.update', 'uses' => 'ApprovalRequestController@update']);
    Route::delete('/delete/{id}', ['as' => 'approval_request.destroy', 'uses' => 'ApprovalRequestController@destroy']);
    
});
