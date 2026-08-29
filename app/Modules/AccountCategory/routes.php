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
Route::group(['prefix' => 'account-category', 'namespace' => 'App\Modules\AccountCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'account_category.index', 'uses' => 'AccountCategoryController@index']);
    Route::any('/search', ['as' => 'account_category.search', 'uses' => 'AccountCategoryController@search']);
    Route::get('/create', ['as' => 'account_category.create', 'uses' => 'AccountCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'account_category.show', 'uses' => 'AccountCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'account_category.preview', 'uses' => 'AccountCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'account_category.print', 'uses' => 'AccountCategoryController@print']);

    Route::post('/store', ['as' => 'account_category.store', 'uses' => 'AccountCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'account_category.edit', 'uses' => 'AccountCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'account_category.update', 'uses' => 'AccountCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'account_category.destroy', 'uses' => 'AccountCategoryController@destroy']);
    
});
