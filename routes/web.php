<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SafeReportController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Rute untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get(
        '/login',
        [AuthController::class, 'showLoginForm']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    )->name('login.process');
});

// Rute terproteksi (sudah login)
Route::middleware('auth')->group(function () {
    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    )
        ->middleware('auth')
        ->name('logout');

    // Area Admin
    Route::middleware([
        'auth',
        'role:admin',
    ])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get(
                '/dashboard',
                [AdminController::class, 'index']
            )->name('dashboard');
        });

    // Area Guru
    Route::middleware('role:guru')
        ->prefix('guru')
        ->group(function () {
            Route::get(
                '/dashboard',
                [GuruController::class, 'index']
            )->name('guru.dashboard');

            Route::get(
                '/heatmap',
                [GuruController::class, 'heatmap']
            )->name('guru.heatmap');

            Route::get(
                '/siswa/{id_siswa}/detail',
                [GuruController::class, 'detailSiswa']
            )->name('guru.siswa.detail');

            Route::get(
                '/siswa/{id_siswa}/observasi',
                [GuruController::class, 'createObservasi']
            )->name('guru.observasi.create');

            Route::post(
                '/siswa/{id_siswa}/observasi',
                [GuruController::class, 'storeObservasi']
            )->name('guru.observasi.store');

            Route::get(
                '/rekomendasi/{id_rekomendasi}/tindak-lanjut',
                [GuruController::class, 'createTindakLanjut']
            )->name('guru.tindak-lanjut.create');

            Route::post(
                '/rekomendasi/{id_rekomendasi}/tindak-lanjut',
                [GuruController::class, 'storeTindakLanjut']
            )->name('guru.tindak-lanjut.store');

            Route::get(
                '/tindak-lanjut/{id_tindak_lanjut}/monitoring/create',
                [GuruController::class, 'createMonitoring']
            )->name('guru.monitoring.create');

            // Menyimpan monitoring
            Route::post(
                '/tindak-lanjut/{id_tindak_lanjut}/monitoring',
                [GuruController::class, 'storeMonitoring']
            )->name('guru.monitoring.store');

            // Daftar tindak lanjut
            Route::get(
                '/tindak-lanjut',
                [GuruController::class, 'indexTindakLanjut']
            )->name('guru.tindak-lanjut.index');

            // Detail tindak lanjut
            Route::get(
                '/tindak-lanjut/{id_tindak_lanjut}',
                [GuruController::class, 'showTindakLanjut']
            )->name('guru.tindak-lanjut.show');

            // Form pembaruan tindak lanjut
            Route::get(
                '/tindak-lanjut/{id_tindak_lanjut}/edit',
                [GuruController::class, 'editTindakLanjut']
            )->name('guru.tindak-lanjut.edit');

            // Menyimpan pembaruan tindak lanjut
            Route::patch(
                '/tindak-lanjut/{id_tindak_lanjut}',
                [GuruController::class, 'updateTindakLanjut']
            )->name('guru.tindak-lanjut.update');

            // Form tambah monitoring

        });

    // Area Siswa
    Route::middleware('role:siswa')->prefix('siswa')->group(function () {
        Route::get('/beranda', function () {
            return view('siswa.beranda');
        })->name('siswa.beranda');

        // Rute Check-in Mingguan
        Route::get('/check-in', [CheckInController::class, 'create'])->name('siswa.checkin.create');
        Route::post('/check-in', [CheckInController::class, 'store'])->name('siswa.checkin.store');

        // Rute Safe Report (sebelumnya)
        Route::get('/safe-report', [SafeReportController::class, 'create'])->name('siswa.report.create');
        Route::post('/safe-report', [SafeReportController::class, 'store'])->name('siswa.report.store');
    });
});
