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
Route::group(['prefix' => 'applicant-support-program', 'namespace' => 'App\Modules\ApplicantSupportProgram\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_support_program.index', 'uses' => 'ApplicantSupportProgramController@index']);
    Route::any('/search', ['as' => 'applicant_support_program.search', 'uses' => 'ApplicantSupportProgramController@search']);
    Route::get('/create', ['as' => 'applicant_support_program.create', 'uses' => 'ApplicantSupportProgramController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_support_program.show', 'uses' => 'ApplicantSupportProgramController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_support_program.preview', 'uses' => 'ApplicantSupportProgramController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_support_program.print', 'uses' => 'ApplicantSupportProgramController@print']);

    Route::post('/store', ['as' => 'applicant_support_program.store', 'uses' => 'ApplicantSupportProgramController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_support_program.edit', 'uses' => 'ApplicantSupportProgramController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_support_program.update', 'uses' => 'ApplicantSupportProgramController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_support_program.destroy', 'uses' => 'ApplicantSupportProgramController@destroy']);
    
});
