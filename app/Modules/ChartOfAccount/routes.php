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

Route::group(['prefix' => 'account', 'namespace' => 'App\Modules\ChartOfAccount\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'account.index', 'uses' => 'ChartOfAccountController@index']);
    Route::any('/search', ['as' => 'account.search', 'uses' => 'ChartOfAccountController@search']);
    Route::get('/create', ['as' => 'account.create', 'uses' => 'ChartOfAccountController@create']);
    Route::get('/show/{id}', ['as' => 'account.show', 'uses' => 'ChartOfAccountController@show']);
    Route::get('/preview/{id}', ['as' => 'account.preview', 'uses' => 'ChartOfAccountController@preview']);
    Route::get('/print/{id}', ['as' => 'account.print', 'uses' => 'ChartOfAccountController@print']);

    Route::post('/store', ['as' => 'account.store', 'uses' => 'ChartOfAccountController@store']);
    Route::get('/edit/{id}', ['as' => 'account.edit', 'uses' => 'ChartOfAccountController@edit']);
    Route::put('/update/{id}', ['as' => 'account.update', 'uses' => 'ChartOfAccountController@update']);
    Route::delete('/delete/{id}', ['as' => 'account.destroy', 'uses' => 'ChartOfAccountController@destroy']);
    Route::get('/getDataTree', ['as' => 'account.getDataTree', 'uses' => 'ChartOfAccountController@getDataTree']);
    Route::get('/getData', ['as' => 'account.getData', 'uses' => 'ChartOfAccountController@getData']);
    Route::get('/tree', ['as' => 'account.getTreeData', 'uses' => 'ChartOfAccountController@getTreeData']);
    Route::get('/getDataWithRecursive', ['as' => 'account.Tree', 'uses' => 'ChartOfAccountController@getDataWithRecursive']);
});
