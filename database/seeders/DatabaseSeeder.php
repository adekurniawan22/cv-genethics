<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            PenggunaSeeder::class,
            ProdukSeeder::class,
            MesinSeeder::class,
            PesananSeeder::class,
            PesananDetailSeeder::class,
            HariLiburSeeder::class,
        ]);
    }
}
