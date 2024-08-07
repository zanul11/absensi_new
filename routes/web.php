<?php

use App\Http\Controllers\AbsenPulangController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalAbsenController;
use App\Http\Controllers\JadwalOperatorController;
use App\Http\Controllers\JadwalShiftController;
use App\Http\Controllers\JenisIzinController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\LaporanAbsenController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanAbsenController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PostingAbsenController;
use App\Http\Controllers\RequestAbsenPulangController;
use App\Http\Controllers\RincianAbsenController;
use App\Http\Controllers\ShiftPegawaiController;
use App\Http\Controllers\TglLiburController;
use App\Http\Controllers\TidakMasukController;
use App\Models\AbsenPulang;
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

    if (Auth::check()) {
        return Auth::user()->role == "super";
    }


    Route::get('pegawai/data', [PegawaiController::class, 'data'])
        ->name('pegawai.data');
    Route::get('pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');
    Route::post('pegawai/import', [PegawaiController::class, 'import_post'])->name('pegawai.import.store');
    Route::get('pegawai/template', [PegawaiController::class, 'template'])->name('pegawai.template');
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

    Route::get('request_absen_pulang/data', [RequestAbsenPulangController::class, 'data'])
        ->name('request_absen_pulang.data');
    Route::post('request_absen_pulang/verifikasi', [RequestAbsenPulangController::class, 'verifikasi'])
        ->name('request_absen_pulang.verifikasi');
    Route::resource('request_absen_pulang', RequestAbsenPulangController::class);

    Route::get('tidak_masuk/data', [TidakMasukController::class, 'data'])
        ->name('tidak_masuk.data');
    Route::post('tidak_masuk/verifikasi', [TidakMasukController::class, 'verifikasi'])
        ->name('tidak_masuk.verifikasi');
    Route::resource('tidak_masuk', TidakMasukController::class);

    Route::resource('posting_absen', PostingAbsenController::class);
    Route::resource('laporan_absen', LaporanAbsenController::class);
    Route::resource('rincian_absen', RincianAbsenController::class);

    Route::resource('informasi', HomeController::class);
    Route::get('/home/posting', [HomeController::class, 'data'])->name('home');

    Route::get('jadwal_shift/data', [JadwalShiftController::class, 'data'])
        ->name('jadwal_shift.data');
    Route::resource('jadwal_shift', JadwalShiftController::class);

    Route::get('shift_pegawai/data', [ShiftPegawaiController::class, 'data'])
        ->name('shift_pegawai.data');
    Route::resource('shift_pegawai', ShiftPegawaiController::class);

    Route::get('jadwal_operator/data', [JadwalOperatorController::class, 'data'])
    ->name('jadwal_operator.data');
Route::resource('jadwal_operator', JadwalOperatorController::class);
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
