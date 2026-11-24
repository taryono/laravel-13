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
Route::group(['prefix' => 'sibling-discount-allocation', 'namespace' => 'App\Modules\SiblingDiscountAllocation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sibling_discount_allocation.index', 'uses' => 'SiblingDiscountAllocationController@index']);
    Route::any('/search', ['as' => 'sibling_discount_allocation.search', 'uses' => 'SiblingDiscountAllocationController@search']);
    Route::get('/create', ['as' => 'sibling_discount_allocation.create', 'uses' => 'SiblingDiscountAllocationController@create']);
    Route::get('/show/{id}', ['as' => 'sibling_discount_allocation.show', 'uses' => 'SiblingDiscountAllocationController@show']);
    Route::get('/preview/{id}', ['as' => 'sibling_discount_allocation.preview', 'uses' => 'SiblingDiscountAllocationController@preview']);
    Route::get('/print/{id}', ['as' => 'sibling_discount_allocation.print', 'uses' => 'SiblingDiscountAllocationController@print']);

    Route::post('/store', ['as' => 'sibling_discount_allocation.store', 'uses' => 'SiblingDiscountAllocationController@store']);
    Route::get('/edit/{id}', ['as' => 'sibling_discount_allocation.edit', 'uses' => 'SiblingDiscountAllocationController@edit']);
    Route::put('/update/{id}', ['as' => 'sibling_discount_allocation.update', 'uses' => 'SiblingDiscountAllocationController@update']);
    Route::delete('/delete/{id}', ['as' => 'sibling_discount_allocation.destroy', 'uses' => 'SiblingDiscountAllocationController@destroy']);
    
});
