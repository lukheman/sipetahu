<?php

use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\AuthController;
use App\Livewire\Admin\ComponentDocs;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DataPenjualanManagement;
use App\Livewire\Admin\DataProduksiManagement;
use App\Livewire\Admin\LaporanPenjualan;
use App\Livewire\Admin\LaporanWma;
use App\Livewire\Admin\PrediksiTahu;
use App\Livewire\Admin\ProdukManagement;
use App\Livewire\Admin\Profile;
use App\Livewire\Admin\UserManagement;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::view('/', 'landing')->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('admin')->middleware('auth:web,pemilik')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/data-produksi', DataProduksiManagement::class)->name('admin.data-produksi');
    Route::get('/data-penjualan', DataPenjualanManagement::class)->name('admin.data-penjualan');
    Route::get('/laporan-penjualan', LaporanPenjualan::class)->name('admin.laporan-penjualan');
    Route::get('/laporan-wma', LaporanWma::class)->name('admin.laporan-wma');
    Route::get('/prediksi-tahu', PrediksiTahu::class)->name('admin.prediksi-tahu');
    Route::get('/produk', ProdukManagement::class)->name('admin.produk');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/profile', Profile::class)->name('admin.profile');
    Route::get('/components', ComponentDocs::class)->name('admin.components');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});
