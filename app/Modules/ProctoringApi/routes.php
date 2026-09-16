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
Route::group(['prefix' => 'proctoring', 'namespace' => 'App\Modules\ProctoringApi\Controllers', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/heartbeat', ['as' => 'proctoring.heartbeat', 'uses' => 'ProctoringApiController@heartbeat']);
    Route::post('/log-activity', ['as' => 'proctoring.log-activity', 'uses' => 'ProctoringApiController@logActivity']);
    Route::post('/flag-session', ['as' => 'proctoring.flag-session', 'uses' => 'ProctoringApiController@flagSession']);
});
