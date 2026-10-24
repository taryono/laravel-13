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
Route::group(['prefix' => 'rkas-approval', 'namespace' => 'App\Modules\RkasApproval\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'rkas_approval.index', 'uses' => 'RkasApprovalController@index']);
    Route::any('/search', ['as' => 'rkas_approval.search', 'uses' => 'RkasApprovalController@search']);
    Route::get('/create', ['as' => 'rkas_approval.create', 'uses' => 'RkasApprovalController@create']);
    Route::get('/show/{id}', ['as' => 'rkas_approval.show', 'uses' => 'RkasApprovalController@show']);
    Route::get('/preview/{id}', ['as' => 'rkas_approval.preview', 'uses' => 'RkasApprovalController@preview']);
    Route::get('/print/{id}', ['as' => 'rkas_approval.print', 'uses' => 'RkasApprovalController@print']);

    Route::post('/store', ['as' => 'rkas_approval.store', 'uses' => 'RkasApprovalController@store']);
    Route::get('/edit/{id}', ['as' => 'rkas_approval.edit', 'uses' => 'RkasApprovalController@edit']);
    Route::put('/update/{id}', ['as' => 'rkas_approval.update', 'uses' => 'RkasApprovalController@update']);
    Route::delete('/delete/{id}', ['as' => 'rkas_approval.destroy', 'uses' => 'RkasApprovalController@destroy']);
    
});
