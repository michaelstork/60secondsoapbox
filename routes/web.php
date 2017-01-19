<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'SoapboxController@index')
	->name('home');

Route::post('/authenticate', 'SoapboxController@authenticate');

Route::get('/{admin}', 'AdminController@index')
	->where('admin', '(dashboard|admin)');

Route::post('/user-info', 'AdminController@userInfo');
Route::post('/resend-invitation', 'AdminController@resendInvitation');

Auth::routes();

// allow logout on get request
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);