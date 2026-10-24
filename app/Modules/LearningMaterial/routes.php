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
Route::group(['prefix' => 'learning-material', 'namespace' => 'App\Modules\LearningMaterial\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'learning_material.index', 'uses' => 'LearningMaterialController@index']);
    Route::any('/search', ['as' => 'learning_material.search', 'uses' => 'LearningMaterialController@search']);
    Route::get('/create', ['as' => 'learning_material.create', 'uses' => 'LearningMaterialController@create']);
    Route::get('/show/{id}', ['as' => 'learning_material.show', 'uses' => 'LearningMaterialController@show']);
    Route::get('/preview/{id}', ['as' => 'learning_material.preview', 'uses' => 'LearningMaterialController@preview']);
    Route::get('/print/{id}', ['as' => 'learning_material.print', 'uses' => 'LearningMaterialController@print']);

    Route::post('/store', ['as' => 'learning_material.store', 'uses' => 'LearningMaterialController@store']);
    Route::get('/edit/{id}', ['as' => 'learning_material.edit', 'uses' => 'LearningMaterialController@edit']);
    Route::put('/update/{id}', ['as' => 'learning_material.update', 'uses' => 'LearningMaterialController@update']);
    Route::delete('/delete/{id}', ['as' => 'learning_material.destroy', 'uses' => 'LearningMaterialController@destroy']);
    
});
