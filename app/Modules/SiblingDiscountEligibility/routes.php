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
Route::group(['prefix' => 'sibling-discount-eligibility', 'namespace' => 'App\Modules\SiblingDiscountEligibility\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sibling_discount_eligibility.index', 'uses' => 'SiblingDiscountEligibilityController@index']);
    Route::any('/search', ['as' => 'sibling_discount_eligibility.search', 'uses' => 'SiblingDiscountEligibilityController@search']);
    Route::get('/create', ['as' => 'sibling_discount_eligibility.create', 'uses' => 'SiblingDiscountEligibilityController@create']);
    Route::get('/show/{id}', ['as' => 'sibling_discount_eligibility.show', 'uses' => 'SiblingDiscountEligibilityController@show']);
    Route::get('/preview/{id}', ['as' => 'sibling_discount_eligibility.preview', 'uses' => 'SiblingDiscountEligibilityController@preview']);
    Route::get('/print/{id}', ['as' => 'sibling_discount_eligibility.print', 'uses' => 'SiblingDiscountEligibilityController@print']);

    Route::post('/store', ['as' => 'sibling_discount_eligibility.store', 'uses' => 'SiblingDiscountEligibilityController@store']);
    Route::get('/edit/{id}', ['as' => 'sibling_discount_eligibility.edit', 'uses' => 'SiblingDiscountEligibilityController@edit']);
    Route::put('/update/{id}', ['as' => 'sibling_discount_eligibility.update', 'uses' => 'SiblingDiscountEligibilityController@update']);
    Route::delete('/delete/{id}', ['as' => 'sibling_discount_eligibility.destroy', 'uses' => 'SiblingDiscountEligibilityController@destroy']);
    
});
