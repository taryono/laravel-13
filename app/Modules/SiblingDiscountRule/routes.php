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
Route::group(['prefix' => 'sibling-discount-rule', 'namespace' => 'App\Modules\SiblingDiscountRule\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sibling_discount_rule.index', 'uses' => 'SiblingDiscountRuleController@index']);
    Route::any('/search', ['as' => 'sibling_discount_rule.search', 'uses' => 'SiblingDiscountRuleController@search']);
    Route::get('/create', ['as' => 'sibling_discount_rule.create', 'uses' => 'SiblingDiscountRuleController@create']);
    Route::get('/show/{id}', ['as' => 'sibling_discount_rule.show', 'uses' => 'SiblingDiscountRuleController@show']);
    Route::get('/preview/{id}', ['as' => 'sibling_discount_rule.preview', 'uses' => 'SiblingDiscountRuleController@preview']);
    Route::get('/print/{id}', ['as' => 'sibling_discount_rule.print', 'uses' => 'SiblingDiscountRuleController@print']);

    Route::post('/store', ['as' => 'sibling_discount_rule.store', 'uses' => 'SiblingDiscountRuleController@store']);
    Route::get('/edit/{id}', ['as' => 'sibling_discount_rule.edit', 'uses' => 'SiblingDiscountRuleController@edit']);
    Route::put('/update/{id}', ['as' => 'sibling_discount_rule.update', 'uses' => 'SiblingDiscountRuleController@update']);
    Route::delete('/delete/{id}', ['as' => 'sibling_discount_rule.destroy', 'uses' => 'SiblingDiscountRuleController@destroy']);
    
});
