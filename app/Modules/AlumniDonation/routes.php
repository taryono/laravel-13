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
Route::group(['prefix' => 'alumni-donation', 'namespace' => 'App\Modules\AlumniDonation\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'alumni_donation.index', 'uses' => 'AlumniDonationController@index']);
    Route::any('/search', ['as' => 'alumni_donation.search', 'uses' => 'AlumniDonationController@search']);
    Route::get('/create', ['as' => 'alumni_donation.create', 'uses' => 'AlumniDonationController@create']);
    Route::get('/show/{id}', ['as' => 'alumni_donation.show', 'uses' => 'AlumniDonationController@show']);
    Route::get('/preview/{id}', ['as' => 'alumni_donation.preview', 'uses' => 'AlumniDonationController@preview']);
    Route::get('/print/{id}', ['as' => 'alumni_donation.print', 'uses' => 'AlumniDonationController@print']);

    Route::post('/store', ['as' => 'alumni_donation.store', 'uses' => 'AlumniDonationController@store']);
    Route::get('/edit/{id}', ['as' => 'alumni_donation.edit', 'uses' => 'AlumniDonationController@edit']);
    Route::put('/update/{id}', ['as' => 'alumni_donation.update', 'uses' => 'AlumniDonationController@update']);
    Route::delete('/delete/{id}', ['as' => 'alumni_donation.destroy', 'uses' => 'AlumniDonationController@destroy']);
    
});
