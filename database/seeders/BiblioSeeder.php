<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * BiblioSeeder — Dummy data buku untuk development & testing.
 *
 * Format kolom sudah mengikuti konvensi SLiMS, sehingga:
 *   - Saat data real dari sekolah datang, tinggal truncate & re-seed dari export SLiMS
 *   - summary_text diisi otomatis dari gabungan kolom utama → langsung siap untuk embedding
 *
 * Cara jalankan:
 *   php artisan db:seed --class=BiblioSeeder
 */
class BiblioSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title'         => 'Laskar Pelangi',
                'sor_title'     => 'Laskar Pelangi',
                'author_main'   => 'Andrea Hirata',
                'publisher_id'  => 'Bentang Pustaka',
                'publish_place' => 'Yogyakarta',
                'publish_year'  => 2005,
                'isbn_issn'     => '978-979-1227-78-2',
                'classification'=> '813',
                'subject'       => 'novel fiksi indonesia pendidikan persahabatan belitung',
                'notes'         => 'Novel tentang perjuangan sekelompok anak-anak miskin di Belitung yang bersemangat mengejar mimpi meski keterbatasan fasilitas sekolah. Mengangkat tema persahabatan, pendidikan, dan semangat pantang menyerah.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Bumi Manusia',
                'sor_title'     => 'Bumi Manusia',
                'author_main'   => 'Pramoedya Ananta Toer',
                'publisher_id'  => 'Hasta Mitra',
                'publish_place' => 'Jakarta',
                'publish_year'  => 1980,
                'isbn_issn'     => '978-979-8659-01-6',
                'classification'=> '813',
                'subject'       => 'novel sejarah kolonialisme belanda indonesia penjajahan',
                'notes'         => 'Novel pertama dari Tetralogi Buru karya Pramoedya Ananta Toer. Mengisahkan Minke, pemuda pribumi Indonesia di zaman kolonial Belanda akhir abad ke-19. Menggambarkan konflik antara nilai tradisional dan modernitas.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Matematika untuk SMA Kelas X',
                'sor_title'     => 'Matematika SMA X',
                'author_main'   => 'Sartono Wirodikromo',
                'publisher_id'  => 'Erlangga',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2020,
                'isbn_issn'     => '978-602-298-123-4',
                'classification'=> '510',
                'subject'       => 'matematika sma kelas 10 aljabar geometri fungsi trigonometri',
                'notes'         => 'Buku teks pelajaran matematika untuk SMA kelas X kurikulum Merdeka. Mencakup materi aljabar, fungsi, persamaan kuadrat, geometri, dan trigonometri dasar disertai latihan soal dan pembahasan.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Biologi Kelas XI SMA',
                'sor_title'     => 'Biologi SMA XI',
                'author_main'   => 'D.A. Pratiwi',
                'publisher_id'  => 'Erlangga',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2021,
                'isbn_issn'     => '978-602-298-456-7',
                'classification'=> '570',
                'subject'       => 'biologi sma kelas 11 sel jaringan sistem organ genetika evolusi',
                'notes'         => 'Buku pelajaran biologi SMA kelas XI. Membahas sistem organ tubuh manusia, jaringan tumbuhan, metabolisme sel, genetika Mendel, dan pengantar evolusi.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Sejarah Indonesia Modern 1200–2004',
                'sor_title'     => 'Sejarah Indonesia Modern',
                'author_main'   => 'M.C. Ricklefs',
                'publisher_id'  => 'Serambi',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2008,
                'isbn_issn'     => '978-979-1247-14-7',
                'classification'=> '959.8',
                'subject'       => 'sejarah indonesia kerajaan kolonial kemerdekaan orde baru reformasi',
                'notes'         => 'Karya sejarawan terkemuka yang menelusuri perjalanan sejarah Indonesia dari era kerajaan Hindu-Buddha hingga reformasi 2004. Digunakan luas sebagai referensi akademik.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Kimia Dasar Jilid 1',
                'sor_title'     => 'Kimia Dasar 1',
                'author_main'   => 'Raymond Chang',
                'publisher_id'  => 'Erlangga',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2019,
                'isbn_issn'     => '978-602-298-789-0',
                'classification'=> '540',
                'subject'       => 'kimia dasar atom molekul stoikiometri larutan termodinamika',
                'notes'         => 'Buku kimia dasar tingkat universitas yang mencakup struktur atom, ikatan kimia, stoikiometri, wujud zat, larutan, dan pengantar termodinamika. Dilengkapi contoh soal dan latihan.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Pemrograman Web dengan PHP dan MySQL',
                'sor_title'     => 'Pemrograman Web PHP MySQL',
                'author_main'   => 'Betha Sidik',
                'publisher_id'  => 'Informatika',
                'publish_place' => 'Bandung',
                'publish_year'  => 2022,
                'isbn_issn'     => '978-602-1514-55-1',
                'classification'=> '005.2',
                'subject'       => 'pemrograman web php mysql database backend teknologi informasi',
                'notes'         => 'Panduan lengkap pemrograman web menggunakan PHP dan MySQL. Membahas sintaks PHP, koneksi database, CRUD, session, keamanan web, hingga membangun aplikasi web sederhana.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Atomic Habits',
                'sor_title'     => 'Atomic Habits',
                'author_main'   => 'James Clear',
                'publisher_id'  => 'Gramedia Pustaka Utama',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2019,
                'isbn_issn'     => '978-602-06-3760-7',
                'classification'=> '158.1',
                'subject'       => 'motivasi produktivitas kebiasaan pengembangan diri psikologi perilaku',
                'notes'         => 'Buku pengembangan diri yang membahas cara membangun kebiasaan baik dan menghilangkan kebiasaan buruk melalui perubahan kecil yang konsisten. Berdasarkan penelitian ilmu perilaku dan psikologi.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Pengantar Ilmu Ekonomi',
                'sor_title'     => 'Pengantar Ekonomi',
                'author_main'   => 'Paul A. Samuelson',
                'publisher_id'  => 'Erlangga',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2018,
                'isbn_issn'     => '978-602-298-321-4',
                'classification'=> '330',
                'subject'       => 'ekonomi mikro makro permintaan penawaran pasar inflasi kebijakan fiskal',
                'notes'         => 'Buku pengantar ilmu ekonomi klasik yang membahas konsep dasar ekonomi mikro dan makro, mekanisme pasar, teori produksi, perdagangan internasional, dan kebijakan ekonomi.',
                'language_id'   => 'id',
            ],
            [
                'title'         => 'Sapiens: Riwayat Singkat Umat Manusia',
                'sor_title'     => 'Sapiens',
                'author_main'   => 'Yuval Noah Harari',
                'publisher_id'  => 'KPG (Kepustakaan Populer Gramedia)',
                'publish_place' => 'Jakarta',
                'publish_year'  => 2017,
                'isbn_issn'     => '978-602-424-383-2',
                'classification'=> '909',
                'subject'       => 'sejarah manusia peradaban revolusi kognitif pertanian industri biologi evolusi',
                'notes'         => 'Buku populer yang menelusuri sejarah umat manusia sejak kemunculan Homo sapiens hingga era modern. Membahas revolusi kognitif, pertanian, penyatuan umat manusia, dan revolusi ilmiah.',
                'language_id'   => 'id',
            ],
        ];

        foreach ($books as &$book) {
            // Buat summary_text otomatis dari kolom utama — ini yang akan di-embed
            $book['summary_text'] = implode('. ', array_filter([
                'Judul: ' . $book['title'],
                'Pengarang: ' . $book['author_main'],
                'Penerbit: ' . ($book['publisher_id'] ?? ''),
                'Tahun: ' . ($book['publish_year'] ?? ''),
                'Subjek: ' . ($book['subject'] ?? ''),
                'Sinopsis: ' . ($book['notes'] ?? ''),
            ]));
            $book['created_at'] = now();
            $book['updated_at'] = now();
        }

        DB::table('biblio')->insert($books);
        $this->command->info('✅ BiblioSeeder: ' . count($books) . ' buku berhasil dimasukkan.');
    }
}