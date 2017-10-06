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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'guest:api'], function () {
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
    Route::get('hitungpesan', 'Apps\SemuaPesanController@hitungPesan');
    Route::resource('semuagpesan', 'Apps\SemuaPesanController');
});
// Route::prefix('api')->group(function() {
    
    
// });