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

Route::post('/authenticate', 'SoapboxApiController@authenticate');
Route::post('/audio-upload', 'SoapboxApiController@audioUpload');
Route::post('/validate-nominee', 'SoapboxApiController@validateNominee');
Route::post('/submission', 'SoapboxApiController@submission');