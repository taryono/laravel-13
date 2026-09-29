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
Route::group(['prefix' => 'applicant-choice', 'namespace' => 'App\Modules\ApplicantChoice\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_choice.index', 'uses' => 'ApplicantChoiceController@index']);
    Route::any('/search', ['as' => 'applicant_choice.search', 'uses' => 'ApplicantChoiceController@search']);
    Route::get('/create', ['as' => 'applicant_choice.create', 'uses' => 'ApplicantChoiceController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_choice.show', 'uses' => 'ApplicantChoiceController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_choice.preview', 'uses' => 'ApplicantChoiceController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_choice.print', 'uses' => 'ApplicantChoiceController@print']);

    Route::post('/store', ['as' => 'applicant_choice.store', 'uses' => 'ApplicantChoiceController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_choice.edit', 'uses' => 'ApplicantChoiceController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_choice.update', 'uses' => 'ApplicantChoiceController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_choice.destroy', 'uses' => 'ApplicantChoiceController@destroy']);
    
});
