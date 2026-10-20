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
Route::group(['prefix' => 'timetable-route', 'namespace' => 'App\Modules\TimetableRoute\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'timetable_route.index', 'uses' => 'TimetableRouteController@index']);
    Route::any('/search', ['as' => 'timetable_route.search', 'uses' => 'TimetableRouteController@search']);
    Route::get('/create', ['as' => 'timetable_route.create', 'uses' => 'TimetableRouteController@create']);
    Route::get('/show/{id}', ['as' => 'timetable_route.show', 'uses' => 'TimetableRouteController@show']);
    Route::get('/preview/{id}', ['as' => 'timetable_route.preview', 'uses' => 'TimetableRouteController@preview']);
    Route::get('/print/{id}', ['as' => 'timetable_route.print', 'uses' => 'TimetableRouteController@print']);

    Route::post('/store', ['as' => 'timetable_route.store', 'uses' => 'TimetableRouteController@store']);
    Route::get('/edit/{id}', ['as' => 'timetable_route.edit', 'uses' => 'TimetableRouteController@edit']);
    Route::put('/update/{id}', ['as' => 'timetable_route.update', 'uses' => 'TimetableRouteController@update']);
    Route::delete('/delete/{id}', ['as' => 'timetable_route.destroy', 'uses' => 'TimetableRouteController@destroy']);
    
});
