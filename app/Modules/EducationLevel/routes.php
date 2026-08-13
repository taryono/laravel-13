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
Route::group(['prefix' => 'education-level', 'namespace' => 'App\Modules\EducationLevel\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'education_level.index', 'uses' => 'EducationLevelController@index']);
    Route::any('/search', ['as' => 'education_level.search', 'uses' => 'EducationLevelController@search']);
    Route::get('/create', ['as' => 'education_level.create', 'uses' => 'EducationLevelController@create']);
    Route::get('/show/{id}', ['as' => 'education_level.show', 'uses' => 'EducationLevelController@show']);
    Route::get('/preview/{id}', ['as' => 'education_level.preview', 'uses' => 'EducationLevelController@preview']);
    Route::get('/print/{id}', ['as' => 'education_level.print', 'uses' => 'EducationLevelController@print']);

    Route::post('/store', ['as' => 'education_level.store', 'uses' => 'EducationLevelController@store']);
    Route::get('/edit/{id}', ['as' => 'education_level.edit', 'uses' => 'EducationLevelController@edit']);
    Route::put('/update/{id}', ['as' => 'education_level.update', 'uses' => 'EducationLevelController@update']);
    Route::delete('/delete/{id}', ['as' => 'education_level.destroy', 'uses' => 'EducationLevelController@destroy']);
    
});
