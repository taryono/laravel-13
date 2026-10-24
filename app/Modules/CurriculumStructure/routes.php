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
Route::group(['prefix' => 'curriculum-structure', 'namespace' => 'App\Modules\CurriculumStructure\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_structure.index', 'uses' => 'CurriculumStructureController@index']);
    Route::any('/search', ['as' => 'curriculum_structure.search', 'uses' => 'CurriculumStructureController@search']);
    Route::get('/create', ['as' => 'curriculum_structure.create', 'uses' => 'CurriculumStructureController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_structure.show', 'uses' => 'CurriculumStructureController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_structure.preview', 'uses' => 'CurriculumStructureController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_structure.print', 'uses' => 'CurriculumStructureController@print']);

    Route::post('/store', ['as' => 'curriculum_structure.store', 'uses' => 'CurriculumStructureController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_structure.edit', 'uses' => 'CurriculumStructureController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_structure.update', 'uses' => 'CurriculumStructureController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_structure.destroy', 'uses' => 'CurriculumStructureController@destroy']);
    
});
