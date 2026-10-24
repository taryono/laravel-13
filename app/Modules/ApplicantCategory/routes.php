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
Route::group(['prefix' => 'applicant-category', 'namespace' => 'App\Modules\ApplicantCategory\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_category.index', 'uses' => 'ApplicantCategoryController@index']);
    Route::any('/search', ['as' => 'applicant_category.search', 'uses' => 'ApplicantCategoryController@search']);
    Route::get('/create', ['as' => 'applicant_category.create', 'uses' => 'ApplicantCategoryController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_category.show', 'uses' => 'ApplicantCategoryController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_category.preview', 'uses' => 'ApplicantCategoryController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_category.print', 'uses' => 'ApplicantCategoryController@print']);

    Route::post('/store', ['as' => 'applicant_category.store', 'uses' => 'ApplicantCategoryController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_category.edit', 'uses' => 'ApplicantCategoryController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_category.update', 'uses' => 'ApplicantCategoryController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_category.destroy', 'uses' => 'ApplicantCategoryController@destroy']);
    
});
