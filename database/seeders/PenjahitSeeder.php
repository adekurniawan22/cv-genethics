<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjahitSeeder extends Seeder
{
    public function run()
    {
        DB::table('penjahit')->insert([
            [
                'nama_penjahit' => 'Penjahit A',
                'alamat' => 'Jl. Kebon Jeruk No. 10, Jakarta',
                'kontak' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_penjahit' => 'Penjahit B',
                'alamat' => 'Jl. Mangga Besar No. 20, Jakarta',
                'kontak' => '082345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
