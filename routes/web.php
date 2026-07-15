<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResepController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\ApotekerDashboardController;
use App\Http\Controllers\ApotekerPesananController;
use App\Http\Controllers\ApotekerVerifikasiController;
use App\Http\Controllers\ApotekerProfilController;
use App\Http\Controllers\KurirDashboardController;
use App\Http\Controllers\KurirPesananController;
use App\Http\Controllers\KurirPengirimanController;
use App\Http\Controllers\KurirRiwayatController;
use App\Http\Controllers\KurirProfilController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Route Login & Register - Apotek Rizki
|--------------------------------------------------------------------------
*/

// Tampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Tampilkan form register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');

// Proses register
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Proses logout (harus login dulu untuk mengaksesnya)
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Dashboard pelanggan
Route::get('/dashboard', [CustomerController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// Detail produk
Route::get('/produk/{product}', [CustomerController::class, 'detail'])
    ->middleware('auth')
    ->name('product.detail');

// Tampilkan form upload resep
Route::get('/resep/upload', [ResepController::class, 'create'])
    ->middleware('auth')
    ->name('resep.upload');

// Proses simpan resep yang diunggah
Route::post('/resep/upload', [ResepController::class, 'store'])
    ->middleware('auth')
    ->name('resep.store');

    // Daftar pesanan pelanggan
Route::get('/pesanan', [PesananController::class, 'index'])
    ->middleware('auth')
    ->name('pesanan.index');
    Route::post('/pesanan/{code}/terima', [PesananController::class, 'konfirmasiDiterima'])
    ->middleware('auth')
    ->name('pesanan.terima');

Route::get('/pesanan/{code}', [PesananController::class, 'show'])
    ->middleware('auth')
    ->name('pesanan.detail');

// Keranjang belanja (sementara belum tersambung ke database — lihat catatan di CartController)
// Keranjang belanja
Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{obat}', [CartController::class, 'store'])->name('cart.add');
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});

Route::get('/staff/login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffLoginController::class, 'login'])->name('staff.login.submit');
Route::post('/staff/logout', [StaffLoginController::class, 'logout'])->name('staff.logout');

Route::middleware('auth')->prefix('apoteker')->name('apoteker.')->group(function () {
    Route::get('/dashboard', [ApotekerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pesanan', [ApotekerPesananController::class, 'index'])->name('pesanan');
    Route::get('/pesanan/{id}', [ApotekerPesananController::class, 'show'])->name('pesanan.detail');
    Route::post('/pesanan/{id}/terima', [ApotekerPesananController::class, 'terima'])->name('pesanan.terima');
    Route::post('/pesanan/{id}/tolak', [ApotekerPesananController::class, 'tolak'])->name('pesanan.tolak');
    Route::post('/pesanan/{id}/proses', [ApotekerPesananController::class, 'proses'])->name('pesanan.proses');
    Route::post('/pesanan/{id}/siap-dikirim', [ApotekerPesananController::class, 'siapDikirim'])->name('pesanan.siapdikirim');

    Route::get('/verifikasi-obat', [ApotekerVerifikasiController::class, 'index'])->name('verifikasi');
    Route::get('/verifikasi-obat/{id}', [ApotekerVerifikasiController::class, 'show'])->name('verifikasi.detail');
    Route::post('/verifikasi-obat/{id}/setujui', [ApotekerVerifikasiController::class, 'setujui'])->name('verifikasi.setujui');
    Route::post('/verifikasi-obat/{id}/tolak', [ApotekerVerifikasiController::class, 'tolak'])->name('verifikasi.tolak');

    Route::get('/profil', [ApotekerProfilController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ApotekerProfilController::class, 'updateProfile'])->name('profil.update');
    Route::post('/profil/password', [ApotekerProfilController::class, 'updatePassword'])->name('profil.password');
});

Route::middleware('auth')->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', [KurirDashboardController::class, 'index'])->name('dashboard');

    Route::get('/pesanan', [KurirPesananController::class, 'index'])->name('pesanan');
    Route::get('/pesanan/{id}', [KurirPesananController::class, 'show'])->name('pesanan.detail');
    Route::post('/pesanan/{id}/ambil', [KurirPesananController::class, 'ambil'])->name('pesanan.ambil');

    Route::get('/pengiriman', [KurirPengirimanController::class, 'index'])->name('pengiriman');
    Route::post('/pengiriman/{id}/selesai', [KurirPengirimanController::class, 'selesai'])->name('pengiriman.selesai');
    Route::post('/pengiriman/{id}/batal', [KurirPengirimanController::class, 'batal'])->name('pengiriman.batal');

    Route::get('/riwayat', [KurirRiwayatController::class, 'index'])->name('riwayat');

    Route::get('/profil', [KurirProfilController::class, 'index'])->name('profil');
    Route::post('/profil/update', [KurirProfilController::class, 'updateProfile'])->name('profil.update');
    Route::post('/profil/password', [KurirProfilController::class, 'updatePassword'])->name('profil.password');
});

Route::middleware('auth')->group(function () {
    Route::get('/alamat/tambah', [AlamatController::class, 'create'])->name('alamat.create');
    Route::post('/alamat/tambah', [AlamatController::class, 'store'])->name('alamat.store');
});

Route::prefix('admin')
    ->middleware(['auth', 'role:owner'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [StaffController::class, 'index'])->name('index');
            Route::get('/create', [StaffController::class, 'create'])->name('create');
            Route::post('/', [StaffController::class, 'store'])->name('store');
            Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');
            Route::put('/{staff}', [StaffController::class, 'update'])->name('update');
            Route::post('/{staff}/toggle', [StaffController::class, 'toggleActive'])->name('toggle');
        });

        Route::prefix('obat')->name('obat.')->group(function () {
            Route::get('/', [ObatController::class, 'index'])->name('index');
            Route::get('/create', [ObatController::class, 'create'])->name('create');
            Route::post('/', [ObatController::class, 'store'])->name('store');
            Route::get('/{obat}/edit', [ObatController::class, 'edit'])->name('edit');
            Route::put('/{obat}', [ObatController::class, 'update'])->name('update');
        });

        Route::prefix('pesanan')->name('pesanan.')->group(function () {
            Route::get('/', [AdminPesananController::class, 'index'])->name('index');
            Route::get('/{pesanan}', [AdminPesananController::class, 'show'])->name('show');
            Route::post('/{pesanan}/assign-kurir', [AdminPesananController::class, 'assignKurir'])->name('assign-kurir');
            Route::post('/{pesanan}/status', [AdminPesananController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{pesanan}', [AdminPesananController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
        });

        // Route untuk Profil menyusul...
    });

    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])
    ->middleware('throttle:3,1')
    ->name('forgot-password.send-otp');

Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->middleware('throttle:5,1')
    ->name('forgot-password.verify-otp');

Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'reset'])
    ->middleware('throttle:5,1')
    ->name('forgot-password.reset');