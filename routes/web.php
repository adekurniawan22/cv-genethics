<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, PenggunaController, DashboardController, MesinController, PenjadwalanController, PesananController, ProdukController, HariLiburController, PesananDetailController};

// Routes untuk login dan logout
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Routes untuk dashboard
Route::middleware(['auth.custom'])->group(function () {

    // Routes untuk Owner
    Route::middleware(['role:owner'])->group(function () {
        // Dashboard
        Route::get('owner/dashboard', [DashboardController::class, 'owner'])->name('owner.dashboard');
        Route::get('owner/dashboard/keuangan/chart-data/{year?}', [DashboardController::class, 'getChartData'])
            ->name('dashboard.keuangan.chart-data');
        Route::get('owner/dashboard/keuangan/pdf-report/{year?}', [DashboardController::class, 'generatePdfReport'])
            ->name('owner.dashboard.keuangan.pdf-report');

        // List Pesanan
        Route::get('owner/pesanan', [PesananController::class, 'index'])->name('owner.pesanan.index');
        Route::get('owner/pesanan/{pesanan}/detail', [PesananController::class, 'detail'])->name('owner.pesanan.detail');

        // CRUD Pengguna
        Route::get('owner/pengguna', [PenggunaController::class, 'index'])->name('owner.pengguna.index');
        Route::get('owner/pengguna/create', [PenggunaController::class, 'create'])->name('owner.pengguna.create');
        Route::post('owner/pengguna', [PenggunaController::class, 'store'])->name('owner.pengguna.store');
        Route::get('owner/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('owner.pengguna.edit');
        Route::put('owner/pengguna/{id}', [PenggunaController::class, 'update'])->name('owner.pengguna.update');
        Route::delete('owner/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('owner.pengguna.destroy');
    });

    // Routes untuk Manajer
    Route::middleware(['role:manajer'])->group(function () {
        // Dashboard
        Route::get('manajer/dashboard', [DashboardController::class, 'manajer'])->name('manajer.dashboard');
        Route::get('manajer/dashboard/keuangan/chart-data/{year?}', [DashboardController::class, 'getChartData'])
            ->name('dashboard.keuangan.chart-data');
        Route::get('manajer/dashboard/keuangan/pdf-report/{year?}', [DashboardController::class, 'generatePdfReport'])
            ->name('manajer.dashboard.keuangan.pdf-report');

        // CRUD HariLibur
        Route::get('manajer/hari-libur', [HariLiburController::class, 'index'])->name('manajer.hari_libur.index');
        Route::get('manajer/hari-libur/create', [HariLiburController::class, 'create'])->name('manajer.hari_libur.create');
        Route::post('manajer/hari-libur', [HariLiburController::class, 'store'])->name('manajer.hari_libur.store');
        Route::get('manajer/hari-libur/{id}/edit', [HariLiburController::class, 'edit'])->name('manajer.hari_libur.edit');
        Route::put('manajer/hari-libur/{id}', [HariLiburController::class, 'update'])->name('manajer.hari_libur.update');
        Route::delete('manajer/hari-libur/{id}', [HariLiburController::class, 'destroy'])->name('manajer.hari_libur.destroy');

        // CRUD Mesin
        Route::get('manajer/mesin', [MesinController::class, 'index'])->name('manajer.mesin.index');
        Route::get('manajer/mesin/create', [MesinController::class, 'create'])->name('manajer.mesin.create');
        Route::post('manajer/mesin', [MesinController::class, 'store'])->name('manajer.mesin.store');
        Route::get('manajer/mesin/{id}/edit', [MesinController::class, 'edit'])->name('manajer.mesin.edit');
        Route::put('manajer/mesin/{id}', [MesinController::class, 'update'])->name('manajer.mesin.update');
        Route::delete('manajer/mesin/{id}', [MesinController::class, 'destroy'])->name('manajer.mesin.destroy');

        // CRUD Produk
        Route::get('manajer/produk', [ProdukController::class, 'index'])->name('manajer.produk.index');
        Route::get('manajer/produk/create', [ProdukController::class, 'create'])->name('manajer.produk.create');
        Route::post('manajer/produk', [ProdukController::class, 'store'])->name('manajer.produk.store');
        Route::get('manajer/produk/{id}/edit', [ProdukController::class, 'edit'])->name('manajer.produk.edit');
        Route::put('manajer/produk/{id}', [ProdukController::class, 'update'])->name('manajer.produk.update');
        Route::delete('manajer/produk/{id}', [ProdukController::class, 'destroy'])->name('manajer.produk.destroy');

        // List Pesanan
        Route::get('manajer/pesanan', [PesananController::class, 'index'])->name('manajer.pesanan.index');
        Route::get('manajer/pesanan/{pesanan}/detail', [PesananController::class, 'detail'])->name('manajer.pesanan.detail');
        Route::get('manajer/pesanan/detail/{pesananDetail}/detail', [PesananDetailController::class, 'detail'])->name('manajer.pesanan.pesanan_detail');

        // List Penjadwalan
        Route::middleware(['role:manajer'])->group(function () {
            Route::get('manajer/penjadwalan', [PenjadwalanController::class, 'index'])
                ->name('manajer.penjadwalan.index')
                ->defaults('limit', 50)
                ->where([
                    'limit' => '[0-9]+',
                    'date' => '\d{4}-\d{2}-\d{2}',
                ]);
        });

        Route::middleware(['role:manajer'])->group(function () {
            Route::get('manajer/penjadwalan/pdf', [PenjadwalanController::class, 'downloadPDF'])
                ->name('manajer.penjadwalan.pdf')
                ->defaults('limit', 50)
                ->where([
                    'limit' => '[0-9]+',
                    'date' => '\d{4}-\d{2}-\d{2}',
                ]);
        });

        // Hari Libur
        // Route::get('manajer/produk', [ProdukController::class, 'index'])->name('manajer.produk.index');
        Route::get('/get-hari-libur', [HariLiburController::class, 'getHariLibur'])->name('get.hari.libur');
        Route::post('/store-hari-libur', [HariLiburController::class, 'storeHariLibur'])->name('store.hari.libur');
        Route::put('/update-hari-libur/{id}', [HariLiburController::class, 'updateHariLibur'])->name('update.hari.libur');
        Route::delete('/delete-hari-libur/{id}', [HariLiburController::class, 'deleteHariLibur'])->name('delete.hari.libur');
    });

    // Routes untuk Admin
    Route::middleware(['role:admin'])->group(function () {
        // Dashboard
        Route::get('admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        // CRD Pesanan
        Route::get('admin/pesanan', [PesananController::class, 'index'])->name('admin.pesanan.index');
        Route::get('admin/pesanan/{pesanan}/detail', [PesananController::class, 'detail'])->name('admin.pesanan.detail');
        Route::get('admin/pesanan/create', [PesananController::class, 'create'])->name('admin.pesanan.create');
        Route::post('admin/pesanan/store', [PesananController::class, 'store'])->name('admin.pesanan.store');
        Route::get('admin/pesanan/{id}/edit', [PesananController::class, 'edit'])->name('admin.pesanan.edit');
        Route::put('admin/pesanan/{id}', [PesananController::class, 'update'])->name('admin.pesanan.update');
        Route::delete('admin/pesanan/{id}', [PesananController::class, 'destroy'])->name('admin.pesanan.destroy');
    });
});
