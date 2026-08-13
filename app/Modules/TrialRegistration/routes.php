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
Route::group(['prefix' => 'trial-registration', 'namespace' => 'App\Modules\TrialRegistration\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'trial-registration.index', 'uses' => 'TrialRegistrationController@index']);
    Route::any('/search', ['as' => 'trial-registration.search', 'uses' => 'TrialRegistrationController@search']);
    Route::get('/create', ['as' => 'trial-registration.create', 'uses' => 'TrialRegistrationController@create']);
    Route::get('/show/{trial-registration}', ['as' => 'trial-registration.show', 'uses' => 'TrialRegistrationController@show']);
    Route::get('/preview/{trial-registration}', ['as' => 'trial-registration.preview', 'uses' => 'TrialRegistrationController@preview']);
    Route::get('/print/{trial-registration}', ['as' => 'trial-registration.print', 'uses' => 'TrialRegistrationController@print']);

    Route::post('/store', ['as' => 'trial-registration.store', 'uses' => 'TrialRegistrationController@store']);
    Route::get('/edit/{trial-registration}', ['as' => 'trial-registration.edit', 'uses' => 'TrialRegistrationController@edit']);
    Route::put('/update/{trial-registration}', ['as' => 'trial-registration.update', 'uses' => 'TrialRegistrationController@update']);
    Route::delete('/delete/{trial-registration}', ['as' => 'trial-registration.destroy', 'uses' => 'TrialRegistrationController@destroy']);
    
});
