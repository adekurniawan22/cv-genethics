<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PesananDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua pesanan_id yang ada
        $pesananIds = DB::table('pesanan')->pluck('pesanan_id');

        // Ambil semua produk_id yang ada
        $produkIds = DB::table('produk')->pluck('produk_id');

        // Iterasi setiap pesanan
        foreach ($pesananIds as $pesananId) {
            // Tentukan jumlah produk untuk pesanan ini (minimal 1, maksimal jumlah produk yang tersedia)
            $jumlahProduk = $faker->numberBetween(1, min(3, count($produkIds)));

            // Acak produk yang akan digunakan dalam pesanan ini
            $produkDipilih = $faker->randomElements($produkIds->toArray(), $jumlahProduk);

            // Tambahkan detail pesanan untuk setiap produk yang dipilih
            foreach ($produkDipilih as $produkId) {
                DB::table('pesanan_detail')->insert([
                    'pesanan_id' => $pesananId,
                    'produk_id' => $produkId,
                    'jumlah' => $faker->numberBetween(10, 150), // Jumlah produk antara 1-5
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
