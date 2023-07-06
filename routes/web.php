<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [LoginController::class, 'showLoginForm']);
Auth::routes([
    "register" => false,
    "confirm" => false,
    "reset" => false
]);

Route::middleware('auth:web')->group(function () {
    Route::get('pengguna/data', [PenggunaController::class, 'data'])
        ->name('pengguna.data');
    Route::resource('pengguna', PenggunaController::class);
    Route::resource('lokasi', HomeController::class);
    Route::resource('informasi', HomeController::class);
    Route::resource('pegawai', HomeController::class);
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
