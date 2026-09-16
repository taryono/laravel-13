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
Route::group(['prefix' => 'admission-timeline', 'namespace' => 'App\Modules\AdmissionTimeline\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_timeline.index', 'uses' => 'AdmissionTimelineController@index']);
    Route::any('/search', ['as' => 'admission_timeline.search', 'uses' => 'AdmissionTimelineController@search']);
    Route::get('/create', ['as' => 'admission_timeline.create', 'uses' => 'AdmissionTimelineController@create']);
    Route::get('/show/{id}', ['as' => 'admission_timeline.show', 'uses' => 'AdmissionTimelineController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_timeline.preview', 'uses' => 'AdmissionTimelineController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_timeline.print', 'uses' => 'AdmissionTimelineController@print']);

    Route::post('/store', ['as' => 'admission_timeline.store', 'uses' => 'AdmissionTimelineController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_timeline.edit', 'uses' => 'AdmissionTimelineController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_timeline.update', 'uses' => 'AdmissionTimelineController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_timeline.destroy', 'uses' => 'AdmissionTimelineController@destroy']);
    
});
