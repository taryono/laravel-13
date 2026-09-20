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

Route::group(['prefix' => 'promotion-criteria', 'namespace' => 'App\Modules\PromotionCriteria\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'promotion_criteria.index', 'uses' => 'PromotionCriteriaController@index']);
    Route::any('/search', ['as' => 'promotion_criteria.search', 'uses' => 'PromotionCriteriaController@search']);
    Route::get('/create', ['as' => 'promotion_criteria.create', 'uses' => 'PromotionCriteriaController@create']);
    Route::get('/show/{id}', ['as' => 'promotion_criteria.show', 'uses' => 'PromotionCriteriaController@show']);
    Route::get('/preview/{id}', ['as' => 'promotion_criteria.preview', 'uses' => 'PromotionCriteriaController@preview']);
    Route::get('/print/{id}', ['as' => 'promotion_criteria.print', 'uses' => 'PromotionCriteriaController@print']);

    Route::post('/store', ['as' => 'promotion_criteria.store', 'uses' => 'PromotionCriteriaController@store']);
    Route::get('/edit/{id}', ['as' => 'promotion_criteria.edit', 'uses' => 'PromotionCriteriaController@edit']);
    Route::put('/update/{id}', ['as' => 'promotion_criteria.update', 'uses' => 'PromotionCriteriaController@update']);
    Route::delete('/delete/{id}', ['as' => 'promotion_criteria.destroy', 'uses' => 'PromotionCriteriaController@destroy']);
});
