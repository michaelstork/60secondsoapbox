<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/user-audio', 'Api\SoapboxApiController@handleAudioUpload');
Route::delete('/user-audio', 'Api\SoapboxApiController@deleteUserAudio');
Route::post('/validate-nominee', 'Api\SoapboxApiController@validateNominee');
Route::post('/submission', 'Api\SoapboxApiController@handleSubmission');