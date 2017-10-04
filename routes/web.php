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

Route::get('notification', 'HomeController@notification');

Route::prefix('api')->group(function() {
    Route::resource('mititi', 'Apps\MititiController');
    Route::resource('kalpen', 'Apps\KalpenController');
    Route::resource('smartebook', 'Apps\SmartebookController');
    Route::resource('jenjang', 'Apps\JenjangController');
    Route::resource('propinsi', 'Apps\PropinsiController');
    Route::resource('kota', 'Apps\KotaController');
    Route::resource('kurikulum', 'Apps\KurikulumController');
    Route::resource('smartexercise', 'Apps\SmartexerciseController');
    Route::resource('pikse', 'Apps\PikseController');
    Route::resource('rumus', 'Apps\RumusController');
    Route::resource('bakmi', 'Apps\BakmiController');
    Route::resource('ketela', 'Apps\KetelaController');
    Route::resource('sekolah', 'Apps\SekolahController');
    Route::resource('banner', 'Apps\BannerController');
    Route::resource('pesan', 'Apps\PesanController');
    Route::resource('user', 'Apps\UserController');
    Route::get('login', 'Apps\AuthenticationController@index');
    Route::post('login', 'Apps\AuthenticationController@doLogin');
    Route::get('hitungpesan', 'Apps\PesanTerbacaController@hitungPesan');
    Route::resource('semuagpesan', 'Apps\PesanTerbacaController');
    
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('user', 'Apps\AuthenticationController@getAuthUser');
});
