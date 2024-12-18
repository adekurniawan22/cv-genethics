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
            ],
            [
                'nama_produk' => 'Hoodie',
                'keterangan_produk' => 'Hoodie dengan bahan fleece nyaman untuk cuaca dingin',
                'harga' => 180000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Kemeja Flanel',
                'keterangan_produk' => 'Kemeja flanel lengan panjang dengan motif kotak-kotak',
                'harga' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
