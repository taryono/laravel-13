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
Route::group(['prefix' => 'cbt-proctoring-evidence', 'namespace' => 'App\Modules\CbtProctoringEvidence\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'cbt_proctoring_evidence.index', 'uses' => 'CbtProctoringEvidenceController@index']);
    Route::any('/search', ['as' => 'cbt_proctoring_evidence.search', 'uses' => 'CbtProctoringEvidenceController@search']);
    Route::get('/create', ['as' => 'cbt_proctoring_evidence.create', 'uses' => 'CbtProctoringEvidenceController@create']);
    Route::get('/show/{id}', ['as' => 'cbt_proctoring_evidence.show', 'uses' => 'CbtProctoringEvidenceController@show']);
    Route::get('/preview/{id}', ['as' => 'cbt_proctoring_evidence.preview', 'uses' => 'CbtProctoringEvidenceController@preview']);
    Route::get('/print/{id}', ['as' => 'cbt_proctoring_evidence.print', 'uses' => 'CbtProctoringEvidenceController@print']);

    Route::post('/store', ['as' => 'cbt_proctoring_evidence.store', 'uses' => 'CbtProctoringEvidenceController@store']);
    Route::get('/edit/{id}', ['as' => 'cbt_proctoring_evidence.edit', 'uses' => 'CbtProctoringEvidenceController@edit']);
    Route::put('/update/{id}', ['as' => 'cbt_proctoring_evidence.update', 'uses' => 'CbtProctoringEvidenceController@update']);
    Route::delete('/delete/{id}', ['as' => 'cbt_proctoring_evidence.destroy', 'uses' => 'CbtProctoringEvidenceController@destroy']);
    
});
