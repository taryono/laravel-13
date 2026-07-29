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
Route::group(['prefix' => 'competence', 'namespace' => 'App\Modules\Competence\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'competence.index', 'uses' => 'CompetenceController@index']);
    Route::any('/search', ['as' => 'competence.search', 'uses' => 'CompetenceController@search']);
    Route::get('/create', ['as' => 'competence.create', 'uses' => 'CompetenceController@create']);
    Route::get('/show/{competence}', ['as' => 'competence.show', 'uses' => 'CompetenceController@show']);
    Route::get('/preview/{competence}', ['as' => 'competence.preview', 'uses' => 'CompetenceController@preview']);
    Route::get('/print/{competence}', ['as' => 'competence.print', 'uses' => 'CompetenceController@print']);

    Route::post('/store', ['as' => 'competence.store', 'uses' => 'CompetenceController@store']);
    Route::get('/edit/{competence}', ['as' => 'competence.edit', 'uses' => 'CompetenceController@edit']);
    Route::put('/update/{competence}', ['as' => 'competence.update', 'uses' => 'CompetenceController@update']);
    Route::delete('/delete/{competence}', ['as' => 'competence.destroy', 'uses' => 'CompetenceController@destroy']);
    
});
