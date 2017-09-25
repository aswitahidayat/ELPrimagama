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
});