<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = Kategori::all()->keyBy('name');

        foreach ($this->dataBuku() as $data) {
            $kat = $kategori->get($data['kategori']) ?? $kategori->first();

            Buku::create([
                'category_id'          => $kat->id,
                'title'                => $data['title'],
                'author'               => $data['author'],
                'publisher'            => $data['publisher'] ?? null,
                'edition'              => $data['edition'] ?? null,
                'language'             => $data['language'] ?? 'Indonesia',
                'isbn_issn'            => $data['isbn'] ?? null,
                'call_number'          => $data['call_number'] ?? null,
                'classification'       => $data['classification'] ?? null,
                'subject'              => $data['subject'] ?? null,
                'physical_description' => $data['physical_description'] ?? null,
                'content_type'         => 'Teks',
                'media_type'           => 'Tanpa Perantara',
                'carrier_type'         => 'Volume',
                'status'               => $data['status'] ?? 'tersedia',
            ]);
        }

        // Buku tambahan acak menggunakan Factory
        $kategoriIds = $kategori->pluck('id')->toArray();
        Buku::factory()
            ->count(50)
            ->sequence(fn ($seq) => [
                'category_id' => $kategoriIds[array_rand($kategoriIds)],
            ])
            ->create();

        $this->command->info('Seeder Buku selesai: ' . Buku::count() . ' buku ditambahkan.');
    }

    private function dataBuku(): array
    {
        return [
            // ── Kesusastraan ─────────────────────────────────────────
            [
                'kategori'             => 'Kesusastraan',
                'title'                => 'Laskar Pelangi',
                'author'               => 'Andrea Hirata',
                'publisher'            => 'Bentang Pustaka',
                'isbn'                 => '978-979-1062-79-9',
                'call_number'          => '813.6 HIR l',
                'classification'       => '813.6',
                'subject'              => 'Novel Indonesia',
                'physical_description' => 'xii, 534 hlm.; 21 cm',
            ],
            [
                'kategori'             => 'Kesusastraan',
                'title'                => 'Bumi Manusia',
                'author'               => 'Pramoedya Ananta Toer',
                'publisher'            => 'Lentera Dipantara',
                'isbn'                 => '978-979-97312-3-2',
                'call_number'          => '813 PRA b',
                'classification'       => '813',
                'subject'              => 'Novel Sejarah Indonesia',
                'physical_description' => 'xxii, 379 hlm.; 21 cm',
            ],
            [
                'kategori'             => 'Kesusastraan',
                'title'                => 'Ayat-Ayat Cinta',
                'author'               => 'Habiburrahman El Shirazy',
                'publisher'            => 'Republika',
                'isbn'                 => '978-979-1102-56-5',
                'call_number'          => '813 HAB a',
                'classification'       => '813',
                'subject'              => 'Novel Islami',
                'physical_description' => 'xvi, 412 hlm.; 20 cm',
            ],
            [
                'kategori'             => 'Kesusastraan',
                'title'                => 'Negeri 5 Menara',
                'author'               => 'Ahmad Fuadi',
                'publisher'            => 'Gramedia Pustaka Utama',
                'isbn'                 => '978-979-22-4837-7',
                'call_number'          => '813 FUA n',
                'classification'       => '813',
                'subject'              => 'Novel Pendidikan',
                'physical_description' => 'xviii, 423 hlm.; 20 cm',
            ],
            [
                'kategori'             => 'Kesusastraan',
                'title'                => 'Perahu Kertas',
                'author'               => 'Dewi Lestari',
                'publisher'            => 'Bentang Pustaka',
                'isbn'                 => '978-979-1231-43-0',
                'call_number'          => '813 LES p',
                'classification'       => '813',
                'subject'              => 'Novel Romansa',
                'physical_description' => 'x, 444 hlm.; 20 cm',
            ],
            [
                'kategori'             => 'Kesusastraan',
                'title'                => 'Tenggelamnya Kapal Van Der Wijck',
                'author'               => 'Hamka',
                'publisher'            => 'Bulan Bintang',
                'call_number'          => '813 HAM t',
                'classification'       => '813',
                'subject'              => 'Novel Klasik Indonesia',
                'physical_description' => 'viii, 216 hlm.; 21 cm',
            ],

            // ── Ilmu-ilmu Sosial ─────────────────────────────────────
            [
                'kategori'             => 'Ilmu-ilmu Sosial',
                'title'                => 'Pendidikan Karakter: Strategi Mendidik Anak di Zaman Global',
                'author'               => 'Doni Koesoema A.',
                'publisher'            => 'Grasindo',
                'isbn'                 => '978-979-025-356-5',
                'call_number'          => '370.11 KOE p',
                'classification'       => '370.11',
                'subject'              => 'Pendidikan Karakter',
                'physical_description' => 'xvi, 320 hlm.; 23 cm',
            ],
            [
                'kategori'             => 'Ilmu-ilmu Sosial',
                'title'                => 'Manajemen Sumber Daya Manusia',
                'author'               => 'Marihot Manullang',
                'publisher'            => 'BPFE Yogyakarta',
                'isbn'                 => '978-979-503-438-7',
                'call_number'          => '658.3 MAN m',
                'classification'       => '658.3',
                'subject'              => 'Manajemen SDM',
                'physical_description' => 'xiv, 276 hlm.; 23 cm',
            ],
            [
                'kategori'             => 'Ilmu-ilmu Sosial',
                'title'                => 'Sosiologi Suatu Pengantar',
                'author'               => 'Soerjono Soekanto',
                'publisher'            => 'Raja Grafindo Persada',
                'isbn'                 => '978-979-421-049-0',
                'call_number'          => '301 SOE s',
                'classification'       => '301',
                'subject'              => 'Sosiologi',
                'physical_description' => 'xxiv, 508 hlm.; 23 cm',
            ],
            [
                'kategori'             => 'Ilmu-ilmu Sosial',
                'title'                => 'Pengantar Ilmu Ekonomi Makroekonomi',
                'author'               => 'Sadono Sukirno',
                'publisher'            => 'Raja Grafindo Persada',
                'isbn'                 => '978-979-421-933-2',
                'call_number'          => '339 SUK p',
                'classification'       => '339',
                'subject'              => 'Ekonomi Makro',
                'physical_description' => 'xx, 442 hlm.; 23 cm',
            ],

            // ── Ilmu-ilmu Terapan ────────────────────────────────────
            [
                'kategori'             => 'Ilmu-ilmu Terapan',
                'title'                => 'Pemrograman PHP & MySQL untuk Pemula',
                'author'               => 'Betha Sidik',
                'publisher'            => 'Informatika Bandung',
                'isbn'                 => '978-602-1514-25-9',
                'call_number'          => '005.133 SID p',
                'classification'       => '005.133',
                'subject'              => 'Pemrograman Web',
                'physical_description' => 'xii, 450 hlm.; 25 cm',
            ],
            [
                'kategori'             => 'Ilmu-ilmu Terapan',
                'title'                => 'Dasar-Dasar Jaringan Komputer',
                'author'               => 'Forouzan, Behrouz A.',
                'publisher'            => 'McGraw Hill Education',
                'isbn'                 => '978-0-07-338065-3',
                'call_number'          => '004.6 FOR d',
                'classification'       => '004.6',
                'subject'              => 'Jaringan Komputer',
                'physical_description' => 'xxvi, 1028 hlm.; 28 cm',
            ],
            [
                'kategori'             => 'Ilmu-ilmu Terapan',
                'title'                => 'Algoritma dan Pemrograman dalam Bahasa Pascal dan C',
                'author'               => 'Rinaldi Munir',
                'publisher'            => 'Informatika Bandung',
                'isbn'                 => '978-602-1514-06-8',
                'call_number'          => '005.133 MUN a',
                'classification'       => '005.133',
                'subject'              => 'Algoritma Pemrograman',
                'physical_description' => 'xiv, 586 hlm.; 25 cm',
            ],
            [
                'kategori'             => 'Ilmu-ilmu Terapan',
                'title'                => 'Rekayasa Perangkat Lunak',
                'author'               => 'Roger S. Pressman',
                'publisher'            => 'Andi Publisher',
                'isbn'                 => '978-979-29-0036-7',
                'call_number'          => '005.1 PRE r',
                'classification'       => '005.1',
                'subject'              => 'Software Engineering',
                'physical_description' => 'xxiv, 864 hlm.; 28 cm',
            ],

            // ── Kesenian ─────────────────────────────────────────────
            [
                'kategori'             => 'Kesenian',
                'title'                => 'Seni Rupa Terapan Nusantara',
                'author'               => 'Mikke Susanto',
                'publisher'            => 'Dicti Art Lab',
                'isbn'                 => '978-602-14254-0-7',
                'call_number'          => '745 SUS s',
                'classification'       => '745',
                'subject'              => 'Seni Rupa Indonesia',
                'physical_description' => 'viii, 210 hlm.; 24 cm; il.',
            ],
            [
                'kategori'             => 'Kesenian',
                'title'                => 'Pengantar Seni Musik untuk Sekolah Menengah',
                'author'               => 'Karl-Edmund Prier SJ',
                'publisher'            => 'Pusat Musik Liturgi',
                'call_number'          => '780 PRI p',
                'classification'       => '780',
                'subject'              => 'Pendidikan Musik',
                'physical_description' => 'x, 148 hlm.; 21 cm; not.',
            ],

            // ── Hiburan dan Olahraga ─────────────────────────────────
            [
                'kategori'             => 'Hiburan dan Olahraga',
                'title'                => 'Panduan Lengkap Sepak Bola',
                'author'               => 'Luxbacher, Joe',
                'publisher'            => 'Raja Grafindo Persada',
                'isbn'                 => '978-979-421-713-0',
                'call_number'          => '796.334 LUX p',
                'classification'       => '796.334',
                'subject'              => 'Sepak Bola',
                'physical_description' => 'xiv, 242 hlm.; 23 cm; il.',
            ],
            [
                'kategori'             => 'Hiburan dan Olahraga',
                'title'                => 'Teknik Dasar Bulu Tangkis',
                'author'               => 'Tohar',
                'publisher'            => 'Dahara Prize',
                'call_number'          => '796.345 TOH t',
                'classification'       => '796.345',
                'subject'              => 'Bulu Tangkis',
                'physical_description' => 'viii, 178 hlm.; 21 cm; il.',
            ],

            // ── Agama ────────────────────────────────────────────────
            [
                'kategori'             => 'Agama',
                'title'                => 'Tafsir Al-Mishbah Volume 1',
                'author'               => 'M. Quraish Shihab',
                'publisher'            => 'Lentera Hati',
                'isbn'                 => '978-979-8689-20-8',
                'call_number'          => '297.12 SHI t',
                'classification'       => '297.12',
                'subject'              => 'Tafsir Al-Quran',
                'physical_description' => 'xlii, 624 hlm.; 24 cm',
            ],
            [
                'kategori'             => 'Agama',
                'title'                => 'Riyadhus Shalihin',
                'author'               => 'Imam An-Nawawi',
                'publisher'            => 'Al-Maktab Al-Islami',
                'call_number'          => '297.4 NAW r',
                'classification'       => '297.4',
                'subject'              => 'Hadits',
                'physical_description' => 'xvi, 826 hlm.; 24 cm',
            ],
            [
                'kategori'             => 'Agama',
                'title'                => 'Fiqih Islam Wa Adillatuhu Jilid 1',
                'author'               => 'Wahbah Az-Zuhaili',
                'publisher'            => 'Gema Insani',
                'isbn'                 => '978-979-561-980-4',
                'call_number'          => '297.5 ZUH f',
                'classification'       => '297.5',
                'subject'              => 'Fiqih Islam',
                'physical_description' => 'xxiv, 732 hlm.; 24 cm',
            ],

            // ── Filsafat ─────────────────────────────────────────────
            [
                'kategori'             => 'Filsafat',
                'title'                => 'Logika: Ilmu Memikir',
                'author'               => 'W. Poespoprodjo',
                'publisher'            => 'Pustaka Grafika',
                'isbn'                 => '978-979-421-325-5',
                'call_number'          => '160 POE l',
                'classification'       => '160',
                'subject'              => 'Logika Filsafat',
                'physical_description' => 'x, 212 hlm.; 21 cm',
            ],
            [
                'kategori'             => 'Filsafat',
                'title'                => 'Filsafat Ilmu: Sebuah Pengantar Populer',
                'author'               => 'Jujun S. Suriasumantri',
                'publisher'            => 'Pustaka Sinar Harapan',
                'isbn'                 => '978-979-416-014-0',
                'call_number'          => '121 SUR f',
                'classification'       => '121',
                'subject'              => 'Filsafat Ilmu',
                'physical_description' => 'xvi, 372 hlm.; 21 cm',
            ],

            // ── Ilmu Pengetahuan Alam ────────────────────────────────
            [
                'kategori'             => 'Ilmu Pengetahuan Alam',
                'title'                => 'Kimia Dasar Konsep-Konsep Inti Jilid 1',
                'author'               => 'Chang, Raymond',
                'publisher'            => 'Erlangga',
                'isbn'                 => '978-979-741-993-0',
                'call_number'          => '540 CHA k',
                'classification'       => '540',
                'subject'              => 'Kimia Dasar',
                'physical_description' => 'xvi, 498 hlm.; 28 cm; il.',
            ],
            [
                'kategori'             => 'Ilmu Pengetahuan Alam',
                'title'                => 'Fisika Dasar Jilid 1',
                'author'               => 'Halliday, David',
                'publisher'            => 'Erlangga',
                'isbn'                 => '978-979-741-734-9',
                'call_number'          => '530 HAL f',
                'classification'       => '530',
                'subject'              => 'Fisika Dasar',
                'physical_description' => 'xiv, 522 hlm.; 28 cm; il.',
            ],
            [
                'kategori'             => 'Ilmu Pengetahuan Alam',
                'title'                => 'Biologi Campbell Jilid 1',
                'author'               => 'Campbell, Neil A.',
                'publisher'            => 'Erlangga',
                'isbn'                 => '978-979-741-612-0',
                'call_number'          => '570 CAM b',
                'classification'       => '570',
                'subject'              => 'Biologi Umum',
                'physical_description' => 'xxiv, 512 hlm.; 28 cm; il.',
            ],
            [
                'kategori'             => 'Ilmu Pengetahuan Alam',
                'title'                => 'Kalkulus Jilid 1',
                'author'               => 'Purcell, Edwin J.',
                'publisher'            => 'Erlangga',
                'isbn'                 => '978-979-741-514-7',
                'call_number'          => '515 PUR k',
                'classification'       => '515',
                'subject'              => 'Kalkulus',
                'physical_description' => 'xii, 532 hlm.; 28 cm',
            ],

            // ── Geografi dan Sejarah ─────────────────────────────────
            [
                'kategori'             => 'Geografi dan Sejarah',
                'title'                => 'Sejarah Indonesia Modern 1200-2008',
                'author'               => 'M.C. Ricklefs',
                'publisher'            => 'Serambi Ilmu Semesta',
                'isbn'                 => '978-979-1112-39-0',
                'call_number'          => '959.8 RIC s',
                'classification'       => '959.8',
                'subject'              => 'Sejarah Indonesia',
                'physical_description' => 'xiv, 766 hlm.; 24 cm; il.',
            ],
            [
                'kategori'             => 'Geografi dan Sejarah',
                'title'                => 'Geografi Regional Dunia',
                'author'               => 'Bintarto, R.',
                'publisher'            => 'BPFE Yogyakarta',
                'call_number'          => '910 BIN g',
                'classification'       => '910',
                'subject'              => 'Geografi Regional',
                'physical_description' => 'x, 312 hlm.; 21 cm; il.',
            ],

            // ── Lainnya ──────────────────────────────────────────────
            [
                'kategori'             => 'Lainnya',
                'title'                => 'Kamus Besar Bahasa Indonesia Edisi Kelima',
                'author'               => 'Badan Pengembangan dan Pembinaan Bahasa',
                'publisher'            => 'Balai Pustaka',
                'isbn'                 => '978-979-407-196-4',
                'call_number'          => '499.221 KAM',
                'classification'       => '499.221',
                'subject'              => 'Kamus Bahasa Indonesia',
                'physical_description' => 'xcvii, 1701 hlm.; 29 cm',
            ],
            [
                'kategori'             => 'Lainnya',
                'title'                => 'Panduan Penulisan Karya Ilmiah',
                'author'               => 'Gorys Keraf',
                'publisher'            => 'Gramedia',
                'call_number'          => '808 KER p',
                'classification'       => '808',
                'subject'              => 'Penulisan Ilmiah',
                'physical_description' => 'xii, 196 hlm.; 21 cm',
            ],
        ];
    }
}