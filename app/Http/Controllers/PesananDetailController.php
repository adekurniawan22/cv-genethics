<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;

class PesananDetailController extends Controller
{
    public function detail($pesananDetailId)
    {
        $pesananDetail = PesananDetail::with([
            'produk' => function ($query) {
                $query->select('produk_id', 'nama_produk', 'harga');
            },
            'pesanan' => function ($query) {
                $query->select('*')
                    ->with([
                        'pengguna' => function ($query) {
                            $query->select('pengguna_id', 'nama', 'email');
                        }
                    ]);
            }
        ])
            ->where('pesanan_detail_id', $pesananDetailId) // Cari berdasarkan pesanan_detail_id
            ->first(); // Ambil satu record saja

        if (!$pesananDetail) {
            return response()->json(['message' => 'Pesanan detail tidak ditemukan'], 404);
        }

        return response()->json($pesananDetail);
    }
}
