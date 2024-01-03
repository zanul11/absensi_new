<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// new route
Route::post('login', [ApiController::class, 'login']);
Route::get('getLokasiUser/{id}', [ApiController::class, 'getLokasiUser']);
Route::post('insertAbsen/{id}/{location}', [ApiController::class, 'insertAbsen']);
Route::get('getAbsenPegawai/{id}', [ApiController::class, 'getAbsenPegawai']);
Route::get('getLokasi', [ApiController::class, 'getLokasi']);
Route::get('getRincianAbsen/{id}', [ApiController::class, 'getRincianAbsen']);
Route::get('getHistoriAbsen/{id}/{tgl1}/{tgl2}', [ApiController::class, 'getHistoriAbsen']);
