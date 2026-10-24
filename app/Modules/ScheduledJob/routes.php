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
Route::group(['prefix' => 'scheduled-job', 'namespace' => 'App\Modules\ScheduledJob\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'scheduled_job.index', 'uses' => 'ScheduledJobController@index']);
    Route::any('/search', ['as' => 'scheduled_job.search', 'uses' => 'ScheduledJobController@search']);
    Route::get('/create', ['as' => 'scheduled_job.create', 'uses' => 'ScheduledJobController@create']);
    Route::get('/show/{id}', ['as' => 'scheduled_job.show', 'uses' => 'ScheduledJobController@show']);
    Route::get('/preview/{id}', ['as' => 'scheduled_job.preview', 'uses' => 'ScheduledJobController@preview']);
    Route::get('/print/{id}', ['as' => 'scheduled_job.print', 'uses' => 'ScheduledJobController@print']);

    Route::post('/store', ['as' => 'scheduled_job.store', 'uses' => 'ScheduledJobController@store']);
    Route::get('/edit/{id}', ['as' => 'scheduled_job.edit', 'uses' => 'ScheduledJobController@edit']);
    Route::put('/update/{id}', ['as' => 'scheduled_job.update', 'uses' => 'ScheduledJobController@update']);
    Route::delete('/delete/{id}', ['as' => 'scheduled_job.destroy', 'uses' => 'ScheduledJobController@destroy']);
    
});
