<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Daftar nama-nama Indonesia
        $namaIndonesia = [
            'Budi Santoso',
            'Ani Wijayanti',
            'Rini Kurniawati',
            'Dewi Lestari',
            'Joko Prasetyo',
            'Siti Rahayu',
            'Ahmad Hidayat',
            'Sri Utami',
            'Bambang Susanto',
            'Rina Maryati',
            'Eko Prabowo',
            'Yuni Astuti',
            'Agus Setiawan',
            'Endang Ratnawati',
            'Hadi Purnomo'
        ];

        // Channel yang valid
        $channels = ['WA', 'SHOPEE', 'TOKOPEDIA'];


        // Generate 50 pesanan
        for ($i = 1; $i <= 50; $i++) {
            // Tentukan status dan tanggal pesanan
            $status = $faker->randomElement(['proses', 'selesai']);

            if ($status === 'selesai') {
                // Untuk status selesai, gunakan tanggal lebih dari 2 minggu yang lalu
                $tanggalPesanan = $faker->dateTimeBetween('-3 months', '-2 weeks')->format('Y-m-d');
            } else {
                // Untuk status proses, gunakan tanggal 3 hari terakhir
                $tanggalPesanan = $faker->dateTimeBetween('-3 days', 'now')->format('Y-m-d');
            }

            // Hitung tanggal pengiriman (9 hari setelah tanggal pesanan)
            $tanggalPengiriman = Carbon::parse($tanggalPesanan)->addDays(9)->format('Y-m-d');

            // Pilih channel secara acak
            $channel = $faker->randomElement($channels);

            // Buat kode pesanan
            $kodePesanan = "{$channel}-{$i}";

            // Pilih nama pemesan secara acak
            $namaPemesan = $faker->randomElement($namaIndonesia);

            // Tambahkan data pesanan
            DB::table('pesanan')->insert([
                'kode_pesanan' => $kodePesanan,
                'nama_pemesan' => $namaPemesan,
                'status' => $status,
                'dibuat_oleh' => 1, // Sesuai spesifikasi
                'channel' => $channel,
                'tanggal_pesanan' => $tanggalPesanan,
                'tanggal_pengiriman' => $tanggalPengiriman,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
