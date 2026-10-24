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
Route::group(['prefix' => 'curriculum-phase', 'namespace' => 'App\Modules\CurriculumPhase\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'curriculum_phase.index', 'uses' => 'CurriculumPhaseController@index']);
    Route::any('/search', ['as' => 'curriculum_phase.search', 'uses' => 'CurriculumPhaseController@search']);
    Route::get('/create', ['as' => 'curriculum_phase.create', 'uses' => 'CurriculumPhaseController@create']);
    Route::get('/show/{id}', ['as' => 'curriculum_phase.show', 'uses' => 'CurriculumPhaseController@show']);
    Route::get('/preview/{id}', ['as' => 'curriculum_phase.preview', 'uses' => 'CurriculumPhaseController@preview']);
    Route::get('/print/{id}', ['as' => 'curriculum_phase.print', 'uses' => 'CurriculumPhaseController@print']);

    Route::post('/store', ['as' => 'curriculum_phase.store', 'uses' => 'CurriculumPhaseController@store']);
    Route::get('/edit/{id}', ['as' => 'curriculum_phase.edit', 'uses' => 'CurriculumPhaseController@edit']);
    Route::put('/update/{id}', ['as' => 'curriculum_phase.update', 'uses' => 'CurriculumPhaseController@update']);
    Route::delete('/delete/{id}', ['as' => 'curriculum_phase.destroy', 'uses' => 'CurriculumPhaseController@destroy']);
    
});
