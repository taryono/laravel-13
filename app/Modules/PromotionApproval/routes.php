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
Route::group(['prefix' => 'promotion-approval', 'namespace' => 'App\Modules\PromotionApproval\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'promotion_approval.index', 'uses' => 'PromotionApprovalController@index']);
    Route::any('/search', ['as' => 'promotion_approval.search', 'uses' => 'PromotionApprovalController@search']);
    Route::get('/create', ['as' => 'promotion_approval.create', 'uses' => 'PromotionApprovalController@create']);
    Route::get('/show/{id}', ['as' => 'promotion_approval.show', 'uses' => 'PromotionApprovalController@show']);
    Route::get('/preview/{id}', ['as' => 'promotion_approval.preview', 'uses' => 'PromotionApprovalController@preview']);
    Route::get('/print/{id}', ['as' => 'promotion_approval.print', 'uses' => 'PromotionApprovalController@print']);

    Route::post('/store', ['as' => 'promotion_approval.store', 'uses' => 'PromotionApprovalController@store']);
    Route::get('/edit/{id}', ['as' => 'promotion_approval.edit', 'uses' => 'PromotionApprovalController@edit']);
    Route::put('/update/{id}', ['as' => 'promotion_approval.update', 'uses' => 'PromotionApprovalController@update']);
    Route::delete('/delete/{id}', ['as' => 'promotion_approval.destroy', 'uses' => 'PromotionApprovalController@destroy']);
    
});
