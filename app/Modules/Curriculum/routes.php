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
Route::group(['prefix' => 'curriculum', 'namespace' => 'App\Modules\Curriculum\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum.index', 'uses' => 'CurriculumController@index']);
    Route::any('/search', ['as' => 'curriculum.search', 'uses' => 'CurriculumController@search']);
    Route::get('/create', ['as' => 'curriculum.create', 'uses' => 'CurriculumController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum.show', 'uses' => 'CurriculumController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum.preview', 'uses' => 'CurriculumController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum.print', 'uses' => 'CurriculumController@print']);

    Route::post('/store', ['as' => 'curriculum.store', 'uses' => 'CurriculumController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum.edit', 'uses' => 'CurriculumController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum.update', 'uses' => 'CurriculumController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum.destroy', 'uses' => 'CurriculumController@destroy']);
    
});
