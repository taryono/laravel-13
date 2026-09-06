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
Route::group(['prefix' => 'deduction-category', 'namespace' => 'App\Modules\DeductionCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'deduction_category.index', 'uses' => 'DeductionCategoryController@index']);
    Route::any('/search', ['as' => 'deduction_category.search', 'uses' => 'DeductionCategoryController@search']);
    Route::get('/create', ['as' => 'deduction_category.create', 'uses' => 'DeductionCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'deduction_category.show', 'uses' => 'DeductionCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'deduction_category.preview', 'uses' => 'DeductionCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'deduction_category.print', 'uses' => 'DeductionCategoryController@print']);

    Route::post('/store', ['as' => 'deduction_category.store', 'uses' => 'DeductionCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'deduction_category.edit', 'uses' => 'DeductionCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'deduction_category.update', 'uses' => 'DeductionCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'deduction_category.destroy', 'uses' => 'DeductionCategoryController@destroy']);
    
});
