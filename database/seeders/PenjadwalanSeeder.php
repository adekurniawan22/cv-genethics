<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjadwalanSeeder extends Seeder
{
    public function run()
    {
        // Menyisipkan 6 penjadwalan dengan pesanan_id dari 1 hingga 6
        DB::table('penjadwalan')->insert([
            [
                'pesanan_id' => 1,
                'urutan_prioritas' => 1,
                'estimasi_selesai' => now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 2,
                'urutan_prioritas' => 2,
                'estimasi_selesai' => now()->addDays(1),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 3,
                'urutan_prioritas' => 3,
                'estimasi_selesai' => now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 4,
                'urutan_prioritas' => 4,
                'estimasi_selesai' => now()->addDays(4),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 5,
                'urutan_prioritas' => 5,
                'estimasi_selesai' => now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pesanan_id' => 6,
                'urutan_prioritas' => 6,
                'estimasi_selesai' => now()->addDays(6),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
