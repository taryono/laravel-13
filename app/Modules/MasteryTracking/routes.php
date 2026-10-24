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
Route::group(['prefix' => 'mastery-tracking', 'namespace' => 'App\Modules\MasteryTracking\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'mastery_tracking.index', 'uses' => 'MasteryTrackingController@index']);
    Route::any('/search', ['as' => 'mastery_tracking.search', 'uses' => 'MasteryTrackingController@search']);
    Route::get('/create', ['as' => 'mastery_tracking.create', 'uses' => 'MasteryTrackingController@create']);
    Route::get('/show/{id}', ['as' => 'mastery_tracking.show', 'uses' => 'MasteryTrackingController@show']);
    Route::get('/preview/{id}', ['as' => 'mastery_tracking.preview', 'uses' => 'MasteryTrackingController@preview']);
    Route::get('/print/{id}', ['as' => 'mastery_tracking.print', 'uses' => 'MasteryTrackingController@print']);

    Route::post('/store', ['as' => 'mastery_tracking.store', 'uses' => 'MasteryTrackingController@store']);
    Route::get('/edit/{id}', ['as' => 'mastery_tracking.edit', 'uses' => 'MasteryTrackingController@edit']);
    Route::put('/update/{id}', ['as' => 'mastery_tracking.update', 'uses' => 'MasteryTrackingController@update']);
    Route::delete('/delete/{id}', ['as' => 'mastery_tracking.destroy', 'uses' => 'MasteryTrackingController@destroy']);
    
});
