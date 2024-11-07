<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, PenggunaController, DashboardController, MesinController, PenjadwalanController, PenjahitController, PesananController, ProdukController, PesananDetailController};

// Routes untuk login dan logout
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Routes untuk dashboard
Route::middleware(['auth.custom'])->group(function () {
    // Routes untuk Owner
    Route::middleware(['role:owner'])->group(function () {
        Route::get('owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');

        // CRUD Pengguna hanya untuk Owner
        // CRUD Pengguna hanya untuk Owner dengan nama rute yang diinginkan
        Route::resource('owner/pengguna', PenggunaController::class)->names([
            'index' => 'owner.pengguna.index',
            'create' => 'owner.pengguna.create',
            'store' => 'owner.pengguna.store',
            'show' => 'owner.pengguna.show',
            'edit' => 'owner.pengguna.edit',
            'update' => 'owner.pengguna.update',
            'destroy' => 'owner.pengguna.destroy',
        ]);

        Route::resource('owner/mesin', MesinController::class)->names([
            'index' => 'owner.mesin.index',
            'create' => 'owner.mesin.create',
            'store' => 'owner.mesin.store',
            'show' => 'owner.mesin.show',
            'edit' => 'owner.mesin.edit',
            'update' => 'owner.mesin.update',
            'destroy' => 'owner.mesin.destroy',
        ]);

        Route::resource('owner/penjahit', PenjahitController::class)->names([
            'index' => 'owner.penjahit.index',
            'create' => 'owner.penjahit.create',
            'store' => 'owner.penjahit.store',
            'show' => 'owner.penjahit.show',
            'edit' => 'owner.penjahit.edit',
            'update' => 'owner.penjahit.update',
            'destroy' => 'owner.penjahit.destroy',
        ]);

        Route::resource('owner/produk', ProdukController::class)->names([
            'index' => 'owner.produk.index',
            'create' => 'owner.produk.create',
            'store' => 'owner.produk.store',
            'show' => 'owner.produk.show',
            'edit' => 'owner.produk.edit',
            'update' => 'owner.produk.update',
            'destroy' => 'owner.produk.destroy',
        ]);

        Route::resource('owner/penjadwalan', PenjadwalanController::class)->names([
            'index' => 'owner.penjadwalan.index',
            'create' => 'owner.penjadwalan.create',
            'store' => 'owner.penjadwalan.store',
            'show' => 'owner.penjadwalan.show',
            'edit' => 'owner.penjadwalan.edit',
            'update' => 'owner.penjadwalan.update',
            'destroy' => 'owner.penjadwalan.destroy',
        ]);

        Route::get('owner/pesanan', [PesananController::class, 'index'])->name('owner.pesanan.index'); // Hanya melihat pesanan
    });

    // Routes untuk Manajer
    Route::middleware(['role:manajer'])->group(function () {
        Route::get('manajer/dashboard', [DashboardController::class, 'manajer'])->name('manajer.dashboard');

        // CRUD Mesin, Penjahit, Produk hanya untuk Manajer
        Route::resource('manajer/mesin', MesinController::class);
        Route::resource('manajer/penjahit', PenjahitController::class);
        Route::resource('manajer/produk', ProdukController::class);

        // Hanya melihat pesanan dan penjadwalan untuk Manajer
        Route::get('manajer/pesanan', [PesananController::class, 'index'])->name('manajer.pesanan.index');
        Route::get('manajer/penjadwalan', [PenjadwalanController::class, 'index'])->name('manajer.penjadwalan.index');
    });

    // Routes untuk Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        // CRUD Pesanan dan Pesanan Detail hanya untuk Admin
        Route::resource('admin/pesanan', PesananController::class);
        Route::resource('admin/pesanan-detail', PesananDetailController::class);
    });
});
