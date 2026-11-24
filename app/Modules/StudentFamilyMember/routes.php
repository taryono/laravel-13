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
Route::group(['prefix' => 'student-family-member', 'namespace' => 'App\Modules\StudentFamilyMember\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'student_family_member.index', 'uses' => 'StudentFamilyMemberController@index']);
    Route::any('/search', ['as' => 'student_family_member.search', 'uses' => 'StudentFamilyMemberController@search']);
    Route::get('/create', ['as' => 'student_family_member.create', 'uses' => 'StudentFamilyMemberController@create']);
    Route::get('/show/{id}', ['as' => 'student_family_member.show', 'uses' => 'StudentFamilyMemberController@show']);
    Route::get('/preview/{id}', ['as' => 'student_family_member.preview', 'uses' => 'StudentFamilyMemberController@preview']);
    Route::get('/print/{id}', ['as' => 'student_family_member.print', 'uses' => 'StudentFamilyMemberController@print']);

    Route::post('/store', ['as' => 'student_family_member.store', 'uses' => 'StudentFamilyMemberController@store']);
    Route::get('/edit/{id}', ['as' => 'student_family_member.edit', 'uses' => 'StudentFamilyMemberController@edit']);
    Route::put('/update/{id}', ['as' => 'student_family_member.update', 'uses' => 'StudentFamilyMemberController@update']);
    Route::delete('/delete/{id}', ['as' => 'student_family_member.destroy', 'uses' => 'StudentFamilyMemberController@destroy']);
    
});
