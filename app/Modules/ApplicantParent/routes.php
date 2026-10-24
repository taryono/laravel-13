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
Route::group(['prefix' => 'applicant-parent', 'namespace' => 'App\Modules\ApplicantParent\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'applicant_parent.index', 'uses' => 'ApplicantParentController@index']);
    Route::any('/search', ['as' => 'applicant_parent.search', 'uses' => 'ApplicantParentController@search']);
    Route::get('/create', ['as' => 'applicant_parent.create', 'uses' => 'ApplicantParentController@create']);
    Route::get('/show/{id}', ['as' => 'applicant_parent.show', 'uses' => 'ApplicantParentController@show']);
    Route::get('/preview/{id}', ['as' => 'applicant_parent.preview', 'uses' => 'ApplicantParentController@preview']);
    Route::get('/print/{id}', ['as' => 'applicant_parent.print', 'uses' => 'ApplicantParentController@print']);

    Route::post('/store', ['as' => 'applicant_parent.store', 'uses' => 'ApplicantParentController@store']);
    Route::get('/edit/{id}', ['as' => 'applicant_parent.edit', 'uses' => 'ApplicantParentController@edit']);
    Route::put('/update/{id}', ['as' => 'applicant_parent.update', 'uses' => 'ApplicantParentController@update']);
    Route::delete('/delete/{id}', ['as' => 'applicant_parent.destroy', 'uses' => 'ApplicantParentController@destroy']);
    
});
