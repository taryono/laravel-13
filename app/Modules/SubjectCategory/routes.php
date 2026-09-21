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
Route::group(['prefix' => 'subject-category', 'namespace' => 'App\Modules\SubjectCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'subject_category.index', 'uses' => 'SubjectCategoryController@index']);
    Route::any('/search', ['as' => 'subject_category.search', 'uses' => 'SubjectCategoryController@search']);
    Route::get('/create', ['as' => 'subject_category.create', 'uses' => 'SubjectCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'subject_category.show', 'uses' => 'SubjectCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'subject_category.preview', 'uses' => 'SubjectCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'subject_category.print', 'uses' => 'SubjectCategoryController@print']);

    Route::post('/store', ['as' => 'subject_category.store', 'uses' => 'SubjectCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'subject_category.edit', 'uses' => 'SubjectCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'subject_category.update', 'uses' => 'SubjectCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'subject_category.destroy', 'uses' => 'SubjectCategoryController@destroy']);
    
});
