<?php

namespace App\Http\Controllers;

use App\Models\{Mesin, Penjahit, Pengguna, Pesanan};

class DashboardController extends Controller
{
    public function owner()
    {
        $totalMesin = Mesin::count();
        $totalPenjahit = Penjahit::count();
        $totalPengguna = Pengguna::where('role', '!=', 'owner')->count();
        $totalPesananSelesai = Pesanan::where('status', 'selesai')->count();
        $totalPesananPending = Pesanan::where('status', 'pending')->count();

        return view('menu.dashboard.owner', [
            'title' => 'Owner Dashboard',
            'totalMesin' => $totalMesin,
            'totalPenjahit' => $totalPenjahit,
            'totalPengguna' => $totalPengguna,
            'totalPesananSelesai' => $totalPesananSelesai,
            'totalPesananPending' => $totalPesananPending,
        ]);
    }

    public function manajer()
    {
        $totalPesananSelesai = Pesanan::where('status', 'selesai')->count();
        $totalPesananPending = Pesanan::where('status', 'pending')->count();

        return view('menu.dashboard.manajer', [
            'title' => 'Manajer Dashboard',
            'totalPesananSelesai' => $totalPesananSelesai,
            'totalPesananPending' => $totalPesananPending,
        ]);
    }
    public function admin()
    {
        return view('menu.dashboard.admin', [
            'title' => 'Admin Dashboard',
        ]);
    }
}
