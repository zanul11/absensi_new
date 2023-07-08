<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalAbsenController;
use App\Http\Controllers\JenisIzinController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\TglLiburController;
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

    Route::get('lokasi/data', [LocationController::class, 'data'])
        ->name('lokasi.data');
    Route::resource('lokasi', LocationController::class);

    Route::get('pegawai/data', [PegawaiController::class, 'data'])
        ->name('pegawai.data');
    Route::resource('pegawai', PegawaiController::class);

    Route::get('jenis_izin/data', [JenisIzinController::class, 'data'])
        ->name('jenis_izin.data');
    Route::resource('jenis_izin', JenisIzinController::class);

    Route::get('tanggal_libur/data', [TglLiburController::class, 'data'])
        ->name('tanggal_libur.data');
    Route::resource('tanggal_libur', TglLiburController::class);

    Route::resource('jadwal_absen', JadwalAbsenController::class);

    Route::get('kehadiran/data', [KehadiranController::class, 'data'])
        ->name('kehadiran.data');
    Route::resource('kehadiran', KehadiranController::class);

    Route::resource('laporan_absen', TglLiburController::class);

    Route::resource('informasi', HomeController::class);
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
