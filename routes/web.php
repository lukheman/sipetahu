<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\ComponentDocs;
use App\Livewire\Admin\DataPenjualanManagement;
use App\Livewire\Admin\LaporanPenjualan;
use App\Livewire\Admin\LaporanWma;
use App\Livewire\Admin\ProdukManagement;
use App\Livewire\Admin\PrediksiTahu;
use App\Http\Controllers\Admin\LogoutController;

// Guest Routes
Route::view('/', 'landing')->name('home');

// Auth Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::get('/daftar-pelanggan', \App\Livewire\Public\RegistrasiPelanggan::class)->name('registrasi-pelanggan');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/data-penjualan', DataPenjualanManagement::class)->name('admin.data-penjualan');
    Route::get('/pelanggan', \App\Livewire\Admin\PelangganManagement::class)->name('admin.pelanggan');
    Route::get('/laporan-penjualan', LaporanPenjualan::class)->name('admin.laporan-penjualan');
    Route::get('/laporan-wma', LaporanWma::class)->name('admin.laporan-wma');
    Route::get('/prediksi-tahu', PrediksiTahu::class)->name('admin.prediksi-tahu');
    Route::get('/produk', ProdukManagement::class)->name('admin.produk');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/profile', Profile::class)->name('admin.profile');
    Route::get('/components', ComponentDocs::class)->name('admin.components');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});