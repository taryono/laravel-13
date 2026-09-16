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
Route::group(['prefix' => 'cbt-proctoring', 'namespace' => 'App\Modules\CbtProctoring\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_proctoring.index', 'uses' => 'CbtProctoringController@index']);
    Route::any('/search', ['as' => 'cbt_proctoring.search', 'uses' => 'CbtProctoringController@search']);
    Route::get('/create', ['as' => 'cbt_proctoring.create', 'uses' => 'CbtProctoringController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_proctoring.show', 'uses' => 'CbtProctoringController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_proctoring.preview', 'uses' => 'CbtProctoringController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_proctoring.print', 'uses' => 'CbtProctoringController@print']);

    Route::post('/store', ['as' => 'cbt_proctoring.store', 'uses' => 'CbtProctoringController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_proctoring.edit', 'uses' => 'CbtProctoringController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_proctoring.update', 'uses' => 'CbtProctoringController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_proctoring.destroy', 'uses' => 'CbtProctoringController@destroy']);
    
});
