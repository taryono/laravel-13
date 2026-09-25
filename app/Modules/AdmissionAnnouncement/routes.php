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
Route::group(['prefix' => 'admission-announcement', 'namespace' => 'App\Modules\AdmissionAnnouncement\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'admission_announcement.index', 'uses' => 'AdmissionAnnouncementController@index']);
    Route::any('/search', ['as' => 'admission_announcement.search', 'uses' => 'AdmissionAnnouncementController@search']);
    Route::get('/create', ['as' => 'admission_announcement.create', 'uses' => 'AdmissionAnnouncementController@create']);
    Route::get('/show/{id}', ['as' => 'admission_announcement.show', 'uses' => 'AdmissionAnnouncementController@show']);
    Route::get('/preview/{id}', ['as' => 'admission_announcement.preview', 'uses' => 'AdmissionAnnouncementController@preview']);
    Route::get('/print/{id}', ['as' => 'admission_announcement.print', 'uses' => 'AdmissionAnnouncementController@print']);

    Route::post('/store', ['as' => 'admission_announcement.store', 'uses' => 'AdmissionAnnouncementController@store']);
    Route::get('/edit/{id}', ['as' => 'admission_announcement.edit', 'uses' => 'AdmissionAnnouncementController@edit']);
    Route::put('/update/{id}', ['as' => 'admission_announcement.update', 'uses' => 'AdmissionAnnouncementController@update']);
    Route::delete('/delete/{id}', ['as' => 'admission_announcement.destroy', 'uses' => 'AdmissionAnnouncementController@destroy']);
    
});
