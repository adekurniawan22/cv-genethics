<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HariLiburSeeder extends Seeder
{
    public function run()
    {
        $hariLibur = [
            [
                'tanggal' => '2024-12-25',
                'keterangan' => 'Hari Natal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-01-01',
                'keterangan' => 'Tahun Baru Masehi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-02-28', // Perbaikan dari 2025-02-29
                'keterangan' => 'Isra Mi’raj Nabi Muhammad SAW',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-03-31',
                'keterangan' => 'Hari Raya Nyepi Tahun Baru Saka 1947',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-04-18',
                'keterangan' => 'Wafat Isa Almasih',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-05-01',
                'keterangan' => 'Hari Buruh Internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-05-18',
                'keterangan' => 'Kenaikan Isa Almasih',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-05-29',
                'keterangan' => 'Hari Raya Waisak 2569 BE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-06-06',
                'keterangan' => 'Hari Lahir Pancasila',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('hari_libur')->insert($hariLibur);
    }
}
