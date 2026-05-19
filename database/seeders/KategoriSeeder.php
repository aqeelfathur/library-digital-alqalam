<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Karya Umum',
            'Filsafat',
            'Agama',
            'Ilmu-ilmu Sosial',
            'Bahasa',
            'Ilmu-ilmu Murni',
            'Ilmu-ilmu Terapan',
            'Kesenian, Hiburan, dan Olahraga',
            'Kesusastraan',
            'Geografi dan Sejarah',
        ];

        foreach ($kategori as $nama) {
            Kategori::create([
                'name' => $nama,
                'slug' => Str::slug($nama),
            ]);
        }

        $this->command->info('Seeder Kategori selesai: ' . count($kategori) . ' kategori ditambahkan.');
    }
}