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
Route::group(['prefix' => 'menu-category', 'namespace' => 'App\Modules\MenuCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'menu_category.index', 'uses' => 'MenuCategoryController@index']);
    Route::any('/search', ['as' => 'menu_category.search', 'uses' => 'MenuCategoryController@search']);
    Route::get('/create', ['as' => 'menu_category.create', 'uses' => 'MenuCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'menu_category.show', 'uses' => 'MenuCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'menu_category.preview', 'uses' => 'MenuCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'menu_category.print', 'uses' => 'MenuCategoryController@print']);

    Route::post('/store', ['as' => 'menu_category.store', 'uses' => 'MenuCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'menu_category.edit', 'uses' => 'MenuCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'menu_category.update', 'uses' => 'MenuCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'menu_category.destroy', 'uses' => 'MenuCategoryController@destroy']);
    
});
