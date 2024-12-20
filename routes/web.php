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
        Route::get('owner/dashboard/keuangan/pdf-report', [DashboardController::class, 'generatePdfReport'])
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
        Route::get('manajer/dashboard/keuangan/pdf-report', [DashboardController::class, 'generatePdfReport'])
            ->name('manajer.dashboard.keuangan.pdf-report');

        // CRUD Hari Libur
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
        Route::get('manajer/penjadwalan', [PenjadwalanController::class, 'index'])
            ->name('manajer.penjadwalan.index')
            ->defaults('limit', 50)
            ->where([
                'limit' => '[0-9]+',
                'date' => '\d{4}-\d{2}-\d{2}',
            ]);

        Route::get('manajer/penjadwalan/pdf', [PenjadwalanController::class, 'downloadPDF'])
            ->name('manajer.penjadwalan.pdf')
            ->defaults('limit', 50)
            ->where([
                'limit' => '[0-9]+',
                'date' => '\d{4}-\d{2}-\d{2}',
            ]);

        // CRUD Hari Libur
        Route::get('manajer/get-hari-libur', [HariLiburController::class, 'getHariLibur'])->name('manajer.hari_libur.index');
        Route::post('manajer/store-hari-libur', [HariLiburController::class, 'storeHariLibur'])->name('manajer.hari_libur.store');
        Route::put('manajer/update-hari-libur/{id}', [HariLiburController::class, 'updateHariLibur'])->name('manajer.hari_libur.update');
        Route::delete('manajer/delete-hari-libur/{id}', [HariLiburController::class, 'deleteHariLibur'])->name('manajer.hari_libur.destroy');
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
        Route::get('admin/cari-nama-pemesan', [PesananController::class, 'searchNamaPemesan']);
    });

    // Routes untuk Super
    Route::middleware(['role:super'])->group(function () {
        // Dashboard
        Route::get('super/dashboard', [DashboardController::class, 'super'])->name('super.dashboard');
        Route::get('super/dashboard/keuangan/chart-data/{year?}', [DashboardController::class, 'getChartData'])
            ->name('dashboard.keuangan.chart-data');
        Route::get('super/dashboard/keuangan/pdf-report', [DashboardController::class, 'generatePdfReport'])
            ->name('super.dashboard.keuangan.pdf-report');

        // CRUD Pengguna
        Route::get('super/pengguna', [PenggunaController::class, 'index'])->name('super.pengguna.index');
        Route::get('super/pengguna/create', [PenggunaController::class, 'create'])->name('super.pengguna.create');
        Route::post('super/pengguna', [PenggunaController::class, 'store'])->name('super.pengguna.store');
        Route::get('super/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('super.pengguna.edit');
        Route::put('super/pengguna/{id}', [PenggunaController::class, 'update'])->name('super.pengguna.update');
        Route::delete('super/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('super.pengguna.destroy');

        // CRUD Hari Libur
        Route::get('super/hari-libur', [HariLiburController::class, 'index'])->name('super.hari_libur.index');
        Route::get('super/hari-libur/create', [HariLiburController::class, 'create'])->name('super.hari_libur.create');
        Route::post('super/hari-libur', [HariLiburController::class, 'store'])->name('super.hari_libur.store');
        Route::get('super/hari-libur/{id}/edit', [HariLiburController::class, 'edit'])->name('super.hari_libur.edit');
        Route::put('super/hari-libur/{id}', [HariLiburController::class, 'update'])->name('super.hari_libur.update');
        Route::delete('super/hari-libur/{id}', [HariLiburController::class, 'destroy'])->name('super.hari_libur.destroy');

        // CRUD Mesin
        Route::get('super/mesin', [MesinController::class, 'index'])->name('super.mesin.index');
        Route::get('super/mesin/create', [MesinController::class, 'create'])->name('super.mesin.create');
        Route::post('super/mesin', [MesinController::class, 'store'])->name('super.mesin.store');
        Route::get('super/mesin/{id}/edit', [MesinController::class, 'edit'])->name('super.mesin.edit');
        Route::put('super/mesin/{id}', [MesinController::class, 'update'])->name('super.mesin.update');
        Route::delete('super/mesin/{id}', [MesinController::class, 'destroy'])->name('super.mesin.destroy');

        // CRUD Produk
        Route::get('super/produk', [ProdukController::class, 'index'])->name('super.produk.index');
        Route::get('super/produk/create', [ProdukController::class, 'create'])->name('super.produk.create');
        Route::post('super/produk', [ProdukController::class, 'store'])->name('super.produk.store');
        Route::get('super/produk/{id}/edit', [ProdukController::class, 'edit'])->name('super.produk.edit');
        Route::put('super/produk/{id}', [ProdukController::class, 'update'])->name('super.produk.update');
        Route::delete('super/produk/{id}', [ProdukController::class, 'destroy'])->name('super.produk.destroy');

        // List Pesanan
        Route::get('super/pesanan', [PesananController::class, 'index'])->name('super.pesanan.index');
        Route::get('super/pesanan/{pesanan}/detail', [PesananController::class, 'detail'])->name('super.pesanan.detail');
        Route::get('super/pesanan/detail/{pesananDetail}/detail', [PesananDetailController::class, 'detail'])->name('super.pesanan.pesanan_detail');
        Route::get('super/pesanan/create', [PesananController::class, 'create'])->name('super.pesanan.create');
        Route::post('super/pesanan/store', [PesananController::class, 'store'])->name('super.pesanan.store');
        Route::get('super/pesanan/{id}/edit', [PesananController::class, 'edit'])->name('super.pesanan.edit');
        Route::put('super/pesanan/{id}', [PesananController::class, 'update'])->name('super.pesanan.update');
        Route::put('super/pesanan/{id}/tandai-selesai', [PesananController::class, 'tandaiPesananSelesai'])->name('super.pesanan.tandai_selesai');
        Route::delete('super/pesanan/{id}', [PesananController::class, 'destroy'])->name('super.pesanan.destroy');
        Route::get('super/cari-nama-pemesan', [PesananController::class, 'searchNamaPemesan']);

        // List Penjadwalan
        Route::get('super/penjadwalan', [PenjadwalanController::class, 'index'])
            ->name('super.penjadwalan.index')
            ->defaults('limit', 50)
            ->where([
                'limit' => '[0-9]+',
                'date' => '\d{4}-\d{2}-\d{2}',
            ]);

        Route::get('super/penjadwalan/pdf', [PenjadwalanController::class, 'downloadPDF'])
            ->name('super.penjadwalan.pdf')
            ->defaults('limit', 50)
            ->where([
                'limit' => '[0-9]+',
                'date' => '\d{4}-\d{2}-\d{2}',
            ]);

        // CRUD Hari Libur
        Route::get('super/get-hari-libur', [HariLiburController::class, 'getHariLibur'])->name('super.hari_libur.index');
        Route::post('super/store-hari-libur', [HariLiburController::class, 'storeHariLibur'])->name('super.hari_libur.store');
        Route::put('super/update-hari-libur/{id}', [HariLiburController::class, 'updateHariLibur'])->name('super.hari_libur.update');
        Route::delete('super/delete-hari-libur/{id}', [HariLiburController::class, 'deleteHariLibur'])->name('super.hari_libur.destroy');
    });
});
