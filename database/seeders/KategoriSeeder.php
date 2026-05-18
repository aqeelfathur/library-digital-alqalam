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
            'Kesusastraan',
            'Ilmu-ilmu Sosial',
            'Ilmu-ilmu Terapan',
            'Kesenian',
            'Hiburan dan Olahraga',
            'Agama',
            'Filsafat',
            'Ilmu Pengetahuan Alam',
            'Geografi dan Sejarah',
            'Lainnya',
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