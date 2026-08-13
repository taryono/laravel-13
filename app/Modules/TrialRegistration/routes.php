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
    Route::get('/', ['as' => 'trial_registration.index', 'uses' => 'TrialRegistrationController@index']);
    Route::any('/search', ['as' => 'trial_registration.search', 'uses' => 'TrialRegistrationController@search']);
    Route::get('/create', ['as' => 'trial_registration.create', 'uses' => 'TrialRegistrationController@create']);
    Route::get('/show/{id}', ['as' => 'trial_registration.show', 'uses' => 'TrialRegistrationController@show']);
    Route::get('/preview/{id}', ['as' => 'trial_registration.preview', 'uses' => 'TrialRegistrationController@preview']);
    Route::get('/print/{id}', ['as' => 'trial_registration.print', 'uses' => 'TrialRegistrationController@print']);

    Route::post('/store', ['as' => 'trial_registration.store', 'uses' => 'TrialRegistrationController@store']);
    Route::get('/edit/{id}', ['as' => 'trial_registration.edit', 'uses' => 'TrialRegistrationController@edit']);
    Route::put('/update/{id}', ['as' => 'trial_registration.update', 'uses' => 'TrialRegistrationController@update']);
    Route::delete('/delete/{id}', ['as' => 'trial_registration.destroy', 'uses' => 'TrialRegistrationController@destroy']);
    
});
