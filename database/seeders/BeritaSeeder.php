<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $dataBerita = [
            [
                'title'        => 'Perpustakaan Al-Qalam Kini Hadir dalam Versi Digital',
                'content'      => "Dengan bangga kami mengumumkan peluncuran sistem perpustakaan digital Al-Qalam. Kini anggota dapat mengakses katalog buku, mengajukan peminjaman, dan memantau status pengembalian secara daring tanpa perlu antri di loket.\n\nSistem baru ini dirancang untuk memudahkan seluruh sivitas akademika dalam mengakses layanan perpustakaan kapan saja dan di mana saja. Cukup login menggunakan akun anggota yang telah diberikan oleh pustakawan, kemudian Anda sudah dapat menelusuri ribuan koleksi buku kami.\n\nFitur unggulan sistem digital ini antara lain pencarian buku berdasarkan judul, penulis, dan kategori, pengajuan peminjaman secara online, serta notifikasi batas waktu pengembalian. Untuk informasi lebih lanjut mengenai cara penggunaan sistem, silakan kunjungi halaman Bantuan atau hubungi pustakawan secara langsung.",
                'published_at' => now()->subDays(1),
            ],
            [
                'title'        => 'Koleksi Buku Baru: 50 Judul Telah Ditambahkan ke Perpustakaan',
                'content'      => "Kabar gembira bagi seluruh anggota perpustakaan Al-Qalam! Kami telah menambahkan 50 judul buku baru yang mencakup berbagai bidang ilmu, mulai dari sains, teknologi, sastra, hingga agama dan filsafat.\n\nBuku-buku baru ini dapat langsung ditelusuri melalui fitur pencarian di halaman Koleksi Buku. Koleksi baru ini hadir untuk memenuhi kebutuhan literasi para pelajar dan mendukung kegiatan belajar mengajar di sekolah.\n\nBeberapa judul unggulan yang baru masuk antara lain karya-karya penulis Indonesia terkemuka serta buku-buku referensi akademik dari penerbit terpercaya. Tidak perlu menunggu lama, segera login ke akun anggota Anda dan temukan buku yang Anda butuhkan!",
                'published_at' => now()->subDays(7),
            ],
            [
                'title'        => 'Jadwal Kunjungan Perpustakaan Selama Ujian Semester',
                'content'      => "Dalam rangka mendukung kegiatan ujian akhir semester, Perpustakaan Al-Qalam akan memperpanjang jam operasional mulai minggu depan hingga ujian selesai.\n\nJadwal khusus selama ujian semester:\n- Senin s.d. Kamis : 07.00 - 18.00 WIB\n- Jumat              : 07.00 - 11.30 WIB\n- Sabtu              : 08.00 - 14.00 WIB\n- Minggu             : Tutup\n\nSelain itu, anggota diperbolehkan untuk membawa laptop dan alat tulis pribadi ke dalam ruang baca perpustakaan selama masa ujian. Kami juga menyediakan ruang diskusi kelompok yang dapat digunakan dengan kapasitas maksimal 6 orang.\n\nMohon menjaga ketenangan, kebersihan, dan ketertiban ruangan demi kenyamanan bersama.",
                'published_at' => now()->subDays(14),
            ],
            [
                'title'        => 'Program Donasi Buku: Berbagi Ilmu untuk Sesama',
                'content'      => "Perpustakaan Al-Qalam dengan bangga membuka program donasi buku bagi seluruh sivitas akademika yang ingin menyumbangkan buku-buku berkualitas untuk memperkaya koleksi perpustakaan.\n\nBuku yang dapat didonasikan harus memenuhi kriteria berikut:\n1. Kondisi fisik masih baik dan layak baca\n2. Bukan termasuk buku yang sudah usang atau tidak relevan dengan kurikulum\n3. Berbahasa Indonesia atau Inggris\n4. Diutamakan terbitan dalam 10 tahun terakhir\n5. Bukan buku yang sudah dimiliki lebih dari 3 eksemplar oleh perpustakaan\n\nBuku donasi dapat diserahkan langsung ke meja pustakawan setiap hari kerja pada jam operasional. Setiap pendonasi akan mendapatkan sertifikat apresiasi resmi dari perpustakaan sebagai bentuk penghargaan atas kepedulian terhadap dunia literasi.",
                'published_at' => now()->subDays(21),
            ],
            [
                'title'        => 'Pelatihan Literasi Informasi untuk Siswa Kelas X',
                'content'      => "Perpustakaan Al-Qalam bekerja sama dengan guru-guru mata pelajaran akan menyelenggarakan pelatihan literasi informasi khusus bagi siswa kelas X pada bulan ini.\n\nPelatihan ini bertujuan untuk membekali siswa baru dengan kemampuan mencari, mengevaluasi, dan menggunakan informasi secara efektif, baik dari sumber tercetak maupun digital.\n\nMateri yang akan dibahas meliputi:\n- Pengenalan sistem klasifikasi dan katalog perpustakaan\n- Teknik pencarian buku yang efektif\n- Cara membaca referensi ilmiah\n- Etika penggunaan informasi dan anti-plagiarisme\n- Penulisan daftar pustaka yang benar\n\nPendaftaran dapat dilakukan melalui pustakawan. Pelatihan akan dilaksanakan dalam sesi kelompok kecil agar lebih efektif dan interaktif.",
                'published_at' => now()->subDays(30),
            ],
        ];

        foreach ($dataBerita as $data) {
            Berita::create([
                'title'        => $data['title'],
                'slug'         => Str::slug($data['title']),
                'content'      => $data['content'],
                'published_at' => $data['published_at'],
            ]);
        }

        $this->command->info('Seeder Berita selesai: ' . count($dataBerita) . ' berita ditambahkan.');
    }
}