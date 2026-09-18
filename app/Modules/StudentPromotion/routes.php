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

Route::group(['prefix' => 'student-promotion', 'namespace' => 'App\Modules\StudentPromotion\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_promotion.index', 'uses' => 'StudentPromotionController@index']);
    Route::any('/search', ['as' => 'student_promotion.search', 'uses' => 'StudentPromotionController@search']);
    Route::get('/create', ['as' => 'student_promotion.create', 'uses' => 'StudentPromotionController@create']);
    Route::get('/show/{id}', ['as' => 'student_promotion.show', 'uses' => 'StudentPromotionController@show']);
    Route::get('/preview/{id}', ['as' => 'student_promotion.preview', 'uses' => 'StudentPromotionController@preview']);
    Route::get('/print/{id}', ['as' => 'student_promotion.print', 'uses' => 'StudentPromotionController@print']);

    Route::post('/store', ['as' => 'student_promotion.store', 'uses' => 'StudentPromotionController@store']);
    Route::get('/edit/{id}', ['as' => 'student_promotion.edit', 'uses' => 'StudentPromotionController@edit']);
    Route::put('/update/{id}', ['as' => 'student_promotion.update', 'uses' => 'StudentPromotionController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_promotion.destroy', 'uses' => 'StudentPromotionController@destroy']);
    Route::post('/generate', ['as' => 'student_promotion.generate', 'uses' => 'StudentPromotionController@generate']);
    Route::post('/finalize/{id}', ['as' => 'student_promotion.finalize', 'uses' => 'StudentPromotionController@finalize']);
});
