<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PSB Online SD
|--------------------------------------------------------------------------
|
| Konfigurasi routing untuk aplikasi PSB Online SD.
|
| Kompetensi:
| - J.620100.017.02 - Pemrograman Terstruktur (route grouping)
| - J.620100.006.01 - Merancang User Experience (URL yang intuitif)
|
*/

// ==========================================
// PUBLIC ROUTES (tanpa autentikasi)
// ==========================================

// Halaman utama / Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pendaftaran siswa baru
Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/sukses/{noPendaftaran}', [PendaftaranController::class, 'success'])->name('pendaftaran.success');
Route::get('/pendaftaran/cetak/{noPendaftaran}', [PendaftaranController::class, 'cetak'])->name('pendaftaran.cetak');

// Cek status pendaftaran
Route::get('/cek-status', [PendaftaranController::class, 'cekStatus'])->name('pendaftaran.cek-status');

// Pengumuman
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');

// ==========================================
// ADMIN ROUTES
// ==========================================

// Login/Logout admin
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected admin routes (memerlukan autentikasi)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manajemen Pendaftar
    Route::get('/pendaftar', [AdminController::class, 'pendaftar'])->name('pendaftar');
    Route::get('/pendaftar/{id}', [AdminController::class, 'detail'])->name('detail');
    Route::put('/pendaftar/{id}/status', [AdminController::class, 'updateStatus'])->name('update-status');
    Route::delete('/pendaftar/{id}', [AdminController::class, 'destroy'])->name('destroy');

    // Pengukuran Stunting (naufalfunction)
    Route::post('/cek-stunting', [AdminController::class, 'naufalfunction'])->name('cek-stunting');
});
