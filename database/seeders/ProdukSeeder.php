<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        DB::table('produk')->insert([
            [
                'nama_produk' => 'Kaos',
                'keterangan_produk' => 'Kaos lengan panjang dengan bahan katun',
                'harga' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Jaket',
                'keterangan_produk' => 'Jaket dengan bahan kulit sintetis',
                'harga' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
