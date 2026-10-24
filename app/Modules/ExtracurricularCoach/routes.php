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
Route::group(['prefix' => 'extracurricular-coach', 'namespace' => 'App\Modules\ExtracurricularCoach\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'extracurricular_coach.index', 'uses' => 'ExtracurricularCoachController@index']);
    Route::any('/search', ['as' => 'extracurricular_coach.search', 'uses' => 'ExtracurricularCoachController@search']);
    Route::get('/create', ['as' => 'extracurricular_coach.create', 'uses' => 'ExtracurricularCoachController@create']);
    Route::get('/show/{id}', ['as' => 'extracurricular_coach.show', 'uses' => 'ExtracurricularCoachController@show']);
    Route::get('/preview/{id}', ['as' => 'extracurricular_coach.preview', 'uses' => 'ExtracurricularCoachController@preview']);
    Route::get('/print/{id}', ['as' => 'extracurricular_coach.print', 'uses' => 'ExtracurricularCoachController@print']);

    Route::post('/store', ['as' => 'extracurricular_coach.store', 'uses' => 'ExtracurricularCoachController@store']);
    Route::get('/edit/{id}', ['as' => 'extracurricular_coach.edit', 'uses' => 'ExtracurricularCoachController@edit']);
    Route::put('/update/{id}', ['as' => 'extracurricular_coach.update', 'uses' => 'ExtracurricularCoachController@update']);
    Route::delete('/delete/{id}', ['as' => 'extracurricular_coach.destroy', 'uses' => 'ExtracurricularCoachController@destroy']);
    
});
