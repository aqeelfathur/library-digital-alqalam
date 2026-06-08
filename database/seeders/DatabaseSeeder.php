<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            UserSeeder::class,
            BukuSeeder::class,
            BeritaSeeder::class,
            InformasiPerpustakaanSeeder::class,
            PeminjamanSeeder::class,
            BiblioSeeder::class,
            ItemsSeeder::class,
            LibraryInfoSeeder::class,
        ]);
    }
}