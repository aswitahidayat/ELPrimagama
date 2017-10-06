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

Route::get('/', function () {
    return view('welcome');
});

Route::get('{path}', function () {
    return view('welcome');
})->where('path', '(.*)');

Route::get('notification', 'HomeController@notification');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user', 'Apps\AuthenticationController@getAuthUser');
});
