<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesananDetailSeeder extends Seeder
{
    public function run()
    {
        DB::table('pesanan_detail')->insert([
            [
                'pesanan_id' => 1, // Pesanan dengan ID 1
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 1, // Pesanan dengan ID 1
                'produk_id' => 2, // Produk dengan ID 2 (Jaket)
                'jumlah' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 2, // Pesanan dengan ID 2
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 2, // Pesanan dengan ID 2
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 3, // Pesanan dengan ID 3
                'produk_id' => 2, // Produk dengan ID 2 (Jaket)
                'jumlah' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 3, // Pesanan dengan ID 3
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 4, // Pesanan dengan ID 4
                'produk_id' => 2, // Produk dengan ID 2 (Jaket)
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 4, // Pesanan dengan ID 4
                'produk_id' => 2, // Produk dengan ID 2 (Jaket)
                'jumlah' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 5, // Pesanan dengan ID 5
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 5, // Pesanan dengan ID 5
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 6, // Pesanan dengan ID 6
                'produk_id' => 2, // Produk dengan ID 2 (Jaket)
                'jumlah' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 6, // Pesanan dengan ID 6
                'produk_id' => 1, // Produk dengan ID 1 (Kaos)
                'jumlah' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
