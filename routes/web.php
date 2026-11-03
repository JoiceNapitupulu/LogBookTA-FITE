<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JanjiBimbinganController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\UjianController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Manajemen & Logbook Tugas Akhir FITE IT Del
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Boleh diakses semua role termasuk Mahasiswa Non-TA)
    Route::get('/', [DashboardController::class, 'index'])->name('workspace');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pustaka Arsip Tugas Akhir FITE (Boleh diakses semua role termasuk Mahasiswa Non-TA)
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::post('/arsip', [ArsipController::class, 'store'])->name('arsip.store');

    // Route yang TIDAK BOLEH diakses Mahasiswa Non-TA
    Route::middleware('ensure.mahasiswa.ta')->group(function () {
        // Logbook Aktivitas & Progres
        Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');
        Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');
        Route::patch('/logbook/{logbook}/review', [LogbookController::class, 'updateReview'])->name('logbook.review');

        // Modul Janji Bimbingan Dua Arah
        Route::get('/bimbingan', [JanjiBimbinganController::class, 'index'])->name('bimbingan.index');
        Route::post('/bimbingan', [JanjiBimbinganController::class, 'store'])->name('bimbingan.store');
        Route::patch('/bimbingan/{janjiBimbingan}/status', [JanjiBimbinganController::class, 'updateStatus'])->name('bimbingan.status');

        // Alur Pendaftaran & Manajemen Ujian TA
        Route::get('/ujian', [UjianController::class, 'index'])->name('ujian.index');
        Route::post('/ujian', [UjianController::class, 'store'])->name('ujian.store');
        Route::patch('/ujian/{ujian}/verifikasi', [UjianController::class, 'verifikasiDokumen'])->name('ujian.verifikasi');
        Route::patch('/ujian/{ujian}/jadwal', [UjianController::class, 'tetapkanJadwal'])->name('ujian.jadwal');
        Route::patch('/ujian/{ujian}/fasilitas', [UjianController::class, 'alokasiFasilitas'])->name('ujian.fasilitas');
    });

    // Panel Koordinator TA (Hanya Koordinator TA, Mahasiswa dilarang keras)
    Route::middleware(['ensure.staff', 'role:koordinator'])->group(function () {
        Route::get('/koordinator', [KoordinatorController::class, 'index'])->name('koordinator.index');
        Route::post('/koordinator/assign-pembimbing', [KoordinatorController::class, 'assignPembimbing'])->name('koordinator.assign');
    });

    // Panel Eksekutif Kaprodi (Hanya Kaprodi, Mahasiswa dilarang keras)
    Route::middleware(['ensure.staff', 'role:kaprodi'])->group(function () {
        Route::get('/kaprodi', [KaprodiController::class, 'index'])->name('kaprodi.index');
        Route::patch('/kaprodi/dosen/{dosen}/toggle-koordinator', [KaprodiController::class, 'toggleKoordinator'])->name('kaprodi.toggle-koordinator');
    });
});
