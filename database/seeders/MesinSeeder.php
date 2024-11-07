<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesinSeeder extends Seeder
{
    public function run()
    {
        DB::table('mesin')->insert([
            [
                'nama_mesin' => 'Mesin Jahit 1',
                'status' => 'aktif',
                'keterangan_mesin' => 'Mesin jahit untuk bahan katun',
                'kapasitas_per_hari' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_mesin' => 'Mesin Jahit 2',
                'status' => 'aktif',
                'keterangan_mesin' => 'Mesin jahit untuk bahan kulit sintetis',
                'kapasitas_per_hari' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
