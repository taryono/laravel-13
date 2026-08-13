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
Route::group(['prefix' => 'eduation-level', 'namespace' => 'App\Modules\EduationLevel\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'eduation-level.index', 'uses' => 'EduationLevelController@index']);
    Route::any('/search', ['as' => 'eduation-level.search', 'uses' => 'EduationLevelController@search']);
    Route::get('/create', ['as' => 'eduation-level.create', 'uses' => 'EduationLevelController@create']);
    Route::get('/show/{eduation-level}', ['as' => 'eduation-level.show', 'uses' => 'EduationLevelController@show']);
    Route::get('/preview/{eduation-level}', ['as' => 'eduation-level.preview', 'uses' => 'EduationLevelController@preview']);
    Route::get('/print/{eduation-level}', ['as' => 'eduation-level.print', 'uses' => 'EduationLevelController@print']);

    Route::post('/store', ['as' => 'eduation-level.store', 'uses' => 'EduationLevelController@store']);
    Route::get('/edit/{eduation-level}', ['as' => 'eduation-level.edit', 'uses' => 'EduationLevelController@edit']);
    Route::put('/update/{eduation-level}', ['as' => 'eduation-level.update', 'uses' => 'EduationLevelController@update']);
    Route::delete('/delete/{eduation-level}', ['as' => 'eduation-level.destroy', 'uses' => 'EduationLevelController@destroy']);
    
});
