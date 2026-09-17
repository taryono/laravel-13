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
Route::group(['prefix' => 'promotion-batch', 'namespace' => 'App\Modules\PromotionBatch\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'promotion_batch.index', 'uses' => 'PromotionBatchController@index']);
    Route::any('/search', ['as' => 'promotion_batch.search', 'uses' => 'PromotionBatchController@search']);
    Route::get('/create', ['as' => 'promotion_batch.create', 'uses' => 'PromotionBatchController@create']);
    Route::get('/show/{id}', ['as' => 'promotion_batch.show', 'uses' => 'PromotionBatchController@show']);
    Route::get('/preview/{id}', ['as' => 'promotion_batch.preview', 'uses' => 'PromotionBatchController@preview']);
    Route::get('/print/{id}', ['as' => 'promotion_batch.print', 'uses' => 'PromotionBatchController@print']);

    Route::post('/store', ['as' => 'promotion_batch.store', 'uses' => 'PromotionBatchController@store']);
    Route::get('/edit/{id}', ['as' => 'promotion_batch.edit', 'uses' => 'PromotionBatchController@edit']);
    Route::put('/update/{id}', ['as' => 'promotion_batch.update', 'uses' => 'PromotionBatchController@update']);
    Route::delete('/delete/{id}', ['as' => 'promotion_batch.destroy', 'uses' => 'PromotionBatchController@destroy']);
    
});
