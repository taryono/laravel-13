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
Route::group(['prefix' => 'student-krs-item', 'namespace' => 'App\Modules\StudentKrsItem\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_krs_item.index', 'uses' => 'StudentKrsItemController@index']);
    Route::any('/search', ['as' => 'student_krs_item.search', 'uses' => 'StudentKrsItemController@search']);
    Route::get('/create', ['as' => 'student_krs_item.create', 'uses' => 'StudentKrsItemController@create']);
    Route::get('/show/{id}', ['as' => 'student_krs_item.show', 'uses' => 'StudentKrsItemController@show']);
    Route::get('/preview/{id}', ['as' => 'student_krs_item.preview', 'uses' => 'StudentKrsItemController@preview']);
    Route::get('/print/{id}', ['as' => 'student_krs_item.print', 'uses' => 'StudentKrsItemController@print']);

    Route::post('/store', ['as' => 'student_krs_item.store', 'uses' => 'StudentKrsItemController@store']);
    Route::get('/edit/{id}', ['as' => 'student_krs_item.edit', 'uses' => 'StudentKrsItemController@edit']);
    Route::put('/update/{id}', ['as' => 'student_krs_item.update', 'uses' => 'StudentKrsItemController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_krs_item.destroy', 'uses' => 'StudentKrsItemController@destroy']);
    
});
