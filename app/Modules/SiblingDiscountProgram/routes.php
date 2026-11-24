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
Route::group(['prefix' => 'sibling-discount-program', 'namespace' => 'App\Modules\SiblingDiscountProgram\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'sibling_discount_program.index', 'uses' => 'SiblingDiscountProgramController@index']);
    Route::any('/search', ['as' => 'sibling_discount_program.search', 'uses' => 'SiblingDiscountProgramController@search']);
    Route::get('/create', ['as' => 'sibling_discount_program.create', 'uses' => 'SiblingDiscountProgramController@create']);
    Route::get('/show/{id}', ['as' => 'sibling_discount_program.show', 'uses' => 'SiblingDiscountProgramController@show']);
    Route::get('/preview/{id}', ['as' => 'sibling_discount_program.preview', 'uses' => 'SiblingDiscountProgramController@preview']);
    Route::get('/print/{id}', ['as' => 'sibling_discount_program.print', 'uses' => 'SiblingDiscountProgramController@print']);

    Route::post('/store', ['as' => 'sibling_discount_program.store', 'uses' => 'SiblingDiscountProgramController@store']);
    Route::get('/edit/{id}', ['as' => 'sibling_discount_program.edit', 'uses' => 'SiblingDiscountProgramController@edit']);
    Route::put('/update/{id}', ['as' => 'sibling_discount_program.update', 'uses' => 'SiblingDiscountProgramController@update']);
    Route::delete('/delete/{id}', ['as' => 'sibling_discount_program.destroy', 'uses' => 'SiblingDiscountProgramController@destroy']);
    
});
