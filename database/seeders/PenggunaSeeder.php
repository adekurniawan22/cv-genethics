<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        DB::table('pengguna')->insert([
            [
                'nama' => 'John Doe',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'status_akun' => 'aktif',
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Jane Smith',
                'email' => 'manajer@example.com',
                'password' => Hash::make('password'),
                'status_akun' => 'aktif',
                'role' => 'manajer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bob Johnson',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'status_akun' => 'aktif',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
