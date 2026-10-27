<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunAdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/sukses/{noPendaftaran}', [PendaftaranController::class, 'success'])->name('pendaftaran.success');
Route::get('/pendaftaran/cetak/{noPendaftaran}', [PendaftaranController::class, 'cetak'])->name('pendaftaran.cetak');

Route::get('/cek-status', [PendaftaranController::class, 'cekStatus'])->name('pendaftaran.cek-status');

Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/admin/daftar', [AkunAdminController::class, 'create'])->name('admin.daftar');
Route::post('/admin/daftar', [AkunAdminController::class, 'store'])->name('admin.daftar.submit');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/pendaftar', [AdminController::class, 'pendaftar'])->name('pendaftar');
    Route::get('/pendaftar/{id}', [AdminController::class, 'detail'])->name('detail');
    Route::put('/pendaftar/{id}/status', [AdminController::class, 'updateStatus'])->name('update-status');
    Route::delete('/pendaftar/{id}', [AdminController::class, 'destroy'])->name('destroy');

    Route::post('/cek-stunting', [AdminController::class, 'naufalfunction'])->name('cek-stunting');

    Route::get('/akun', [AkunAdminController::class, 'index'])->name('akun');
    Route::post('/akun/{user}/verifikasi', [AkunAdminController::class, 'verify'])->name('akun.verify');
    Route::delete('/akun/{user}', [AkunAdminController::class, 'destroy'])->name('akun.destroy');
});
