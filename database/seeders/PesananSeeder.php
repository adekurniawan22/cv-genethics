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
                'status' => 'pending',
                'dibuat_oleh' => 1, // Pengguna dengan ID 1
                'channel' => 'Online',
                'tanggal_pesanan' => now(),
                'tanggal_pengiriman' => now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'selesai',
                'dibuat_oleh' => 2, // Pengguna dengan ID 2
                'channel' => 'Offline',
                'tanggal_pesanan' => now()->subDays(5),
                'tanggal_pengiriman' => now()->subDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'pending',
                'dibuat_oleh' => 3, // Pengguna dengan ID 3
                'channel' => 'Online',
                'tanggal_pesanan' => now()->subDays(1),
                'tanggal_pengiriman' => now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'selesai',
                'dibuat_oleh' => 1, // Pengguna dengan ID 1
                'channel' => 'Offline',
                'tanggal_pesanan' => now()->subDays(10),
                'tanggal_pengiriman' => now()->subDays(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'pending',
                'dibuat_oleh' => 2, // Pengguna dengan ID 2
                'channel' => 'Online',
                'tanggal_pesanan' => now()->subDays(3),
                'tanggal_pengiriman' => now()->addDays(4),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'status' => 'selesai',
                'dibuat_oleh' => 3, // Pengguna dengan ID 3
                'channel' => 'Offline',
                'tanggal_pesanan' => now()->subDays(7),
                'tanggal_pengiriman' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
