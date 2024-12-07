<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesananSeeder extends Seeder
{
    public function run()
    {
        DB::table('pesanan')->insert([
            [
                'kode_pesanan' => 'PESANAN/12/2024/1',
                'status' => 'proses',
                'dibuat_oleh' => 1, // Pengguna dengan ID 1
                'channel' => 'Online',
                'tanggal_pesanan' => now(),
                'tanggal_pengiriman' => now()->addDays(9),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pesanan' => 'PESANAN/12/2024/2',
                'status' => 'selesai',
                'dibuat_oleh' => 2, // Pengguna dengan ID 2
                'channel' => 'Offline',
                'tanggal_pesanan' => now(),
                'tanggal_pengiriman' => now()->subDays(9),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pesanan' => 'PESANAN/12/2024/3',
                'status' => 'proses',
                'dibuat_oleh' => 3, // Pengguna dengan ID 3
                'channel' => 'Online',
                'tanggal_pesanan' => now()->subDays(1),
                'tanggal_pengiriman' => now()->subDays(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pesanan' => 'PESANAN/12/2024/4',
                'status' => 'selesai',
                'dibuat_oleh' => 1, // Pengguna dengan ID 1
                'channel' => 'Offline',
                'tanggal_pesanan' => now()->subDays(2),
                'tanggal_pengiriman' => now()->subDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pesanan' => 'PESANAN/12/2024/5',
                'status' => 'proses',
                'dibuat_oleh' => 2, // Pengguna dengan ID 2
                'channel' => 'Online',
                'tanggal_pesanan' => now()->subDays(4),
                'tanggal_pengiriman' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_pesanan' => 'PESANAN/12/2024/5',
                'status' => 'selesai',
                'dibuat_oleh' => 3, // Pengguna dengan ID 3
                'channel' => 'Offline',
                'tanggal_pesanan' => now()->subDays(4),
                'tanggal_pengiriman' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
