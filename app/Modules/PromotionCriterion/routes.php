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
Route::group(['prefix' => 'promotion-criterion', 'namespace' => 'App\Modules\PromotionCriterion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'promotion_criterion.index', 'uses' => 'PromotionCriterionController@index']);
    Route::any('/search', ['as' => 'promotion_criterion.search', 'uses' => 'PromotionCriterionController@search']);
    Route::get('/create', ['as' => 'promotion_criterion.create', 'uses' => 'PromotionCriterionController@create']);
    Route::get('/show/{id}', ['as' => 'promotion_criterion.show', 'uses' => 'PromotionCriterionController@show']);
    Route::get('/preview/{id}', ['as' => 'promotion_criterion.preview', 'uses' => 'PromotionCriterionController@preview']);
    Route::get('/print/{id}', ['as' => 'promotion_criterion.print', 'uses' => 'PromotionCriterionController@print']);

    Route::post('/store', ['as' => 'promotion_criterion.store', 'uses' => 'PromotionCriterionController@store']);
    Route::get('/edit/{id}', ['as' => 'promotion_criterion.edit', 'uses' => 'PromotionCriterionController@edit']);
    Route::put('/update/{id}', ['as' => 'promotion_criterion.update', 'uses' => 'PromotionCriterionController@update']);
    Route::delete('/delete/{id}', ['as' => 'promotion_criterion.destroy', 'uses' => 'PromotionCriterionController@destroy']);
    
});
