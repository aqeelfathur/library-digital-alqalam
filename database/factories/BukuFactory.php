<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    public function definition(): array
    {
        $awalan = [
            'Pengantar', 'Dasar-dasar', 'Panduan Lengkap', 'Teori dan Praktik',
            'Konsep dan Aplikasi', 'Metodologi', 'Kajian', 'Analisis Mendalam',
            'Penerapan', 'Prinsip-prinsip', 'Strategi', 'Teknik',
        ];

        $bidang = [
            'Matematika Terapan', 'Fisika Modern', 'Biologi Molekuler', 'Kimia Organik',
            'Ekonomi Digital', 'Sosiologi Pendidikan', 'Psikologi Perkembangan',
            'Sejarah Peradaban', 'Geografi Lingkungan', 'Bahasa Indonesia',
            'Bahasa Inggris Bisnis', 'Teknologi Informasi', 'Kewirausahaan Sosial',
            'Akuntansi Keuangan', 'Manajemen Strategis', 'Hukum Perdata',
            'Ilmu Komunikasi', 'Hubungan Internasional', 'Administrasi Publik',
        ];

        $penerbit = [
            'Erlangga', 'Gramedia Pustaka Utama', 'Bumi Aksara',
            'Salemba Empat', 'Andi Publisher', 'Raja Grafindo Persada',
            'Alfabeta', 'Rosda', 'Grasindo', 'Prenada Media Group',
            'Kencana', 'Refika Aditama', 'Pustaka Pelajar',
        ];

        return [
            'category_id'          => 1,
            'title'                => fake()->randomElement($awalan) . ' ' . fake()->randomElement($bidang),
            'author'               => fake('id_ID')->name(),
            'publisher'            => fake()->randomElement($penerbit),
            'edition'              => fake()->optional(0.6)->randomElement(['Edisi ke-1', 'Edisi ke-2', 'Edisi ke-3', 'Edisi Revisi']),
            'language'             => 'Indonesia',
            'isbn_issn'            => fake()->optional(0.8)->isbn13(),
            'call_number'          => fake()->numerify('###.## ###'),
            'classification'       => fake()->numerify('###.##'),
            'subject'              => fake()->randomElement($bidang),
            'physical_description' => fake()->randomElement([
                'viii, 256 hlm.; 23 cm',
                'xii, 380 hlm.; 21 cm',
                'xvi, 480 hlm.; 25 cm',
                'x, 312 hlm.; 23 cm',
                'xx, 560 hlm.; 28 cm; il.',
            ]),
            'content_type' => 'Teks',
            'media_type'   => 'Tanpa Perantara',
            'carrier_type' => 'Volume',
            'status'       => 'tersedia',
            'image_url'    => null,
            'publication_year' => fake()->optional(0.8)->numberBetween(1990, 2024),
            'location'         => fake()->optional(0.7)->randomElement(['Rak A', 'Rak B', 'Rak C', 'Perpustakaan Utama']),
            'collection_type'  => fake()->optional(0.9)->randomElement(['buku', 'majalah', 'jurnal', 'ebook']),
            'gmd_type'         => fake()->optional(0.8)->randomElement(['Teks', 'Audio', 'Video', 'Digital']),
            'description'      => fake()->optional(0.6)->sentences(2, true),
        ];
    }

    public function isTersedia(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'tersedia',
        ]);
    }

    public function dipinjam(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'dipinjam',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}