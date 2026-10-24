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
Route::group(['prefix' => 'library-membership', 'namespace' => 'App\Modules\LibraryMembership\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'library_membership.index', 'uses' => 'LibraryMembershipController@index']);
    Route::any('/search', ['as' => 'library_membership.search', 'uses' => 'LibraryMembershipController@search']);
    Route::get('/create', ['as' => 'library_membership.create', 'uses' => 'LibraryMembershipController@create']);
    Route::get('/show/{id}', ['as' => 'library_membership.show', 'uses' => 'LibraryMembershipController@show']);
    Route::get('/preview/{id}', ['as' => 'library_membership.preview', 'uses' => 'LibraryMembershipController@preview']);
    Route::get('/print/{id}', ['as' => 'library_membership.print', 'uses' => 'LibraryMembershipController@print']);

    Route::post('/store', ['as' => 'library_membership.store', 'uses' => 'LibraryMembershipController@store']);
    Route::get('/edit/{id}', ['as' => 'library_membership.edit', 'uses' => 'LibraryMembershipController@edit']);
    Route::put('/update/{id}', ['as' => 'library_membership.update', 'uses' => 'LibraryMembershipController@update']);
    Route::delete('/delete/{id}', ['as' => 'library_membership.destroy', 'uses' => 'LibraryMembershipController@destroy']);
    
});
