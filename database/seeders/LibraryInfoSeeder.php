<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * LibraryInfoSeeder — Knowledge base informasi perpustakaan SMAMDA.
 *
 * Ini adalah "dokumen" yang akan di-embed dan dijadikan konteks RAG
 * untuk pertanyaan seputar operasional perpustakaan (bukan buku).
 *
 * Edit content sesuai informasi real dari pihak sekolah sebelum deployment.
 *
 * Cara jalankan:
 *   php artisan db:seed --class=LibraryInfoSeeder
 */
class LibraryInfoSeeder extends Seeder
{
    public function run(): void
    {
        $infos = [
            // --- Jam Operasional ---
            [
                'category' => 'jam_operasional',
                'title'    => 'Jam Buka Perpustakaan SMAMDA',
                'content'  => 'Perpustakaan SMAMDA (SMA Muhammadiyah 2 Surabaya) buka setiap hari Senin hingga Sabtu. Jam operasional: Senin–Jumat pukul 07.00–16.00 WIB, Sabtu pukul 07.00–12.00 WIB. Perpustakaan tutup pada hari Minggu dan hari libur nasional.',
            ],
            [
                'category' => 'jam_operasional',
                'title'    => 'Jadwal Istirahat Petugas',
                'content'  => 'Layanan perpustakaan mungkin terbatas pada jam istirahat sekolah pukul 12.00–12.30 WIB karena petugas perpustakaan juga beristirahat. Siswa tetap dapat membaca di ruang perpustakaan namun peminjaman dan pengembalian dilayani kembali setelah pukul 12.30 WIB.',
            ],

            // --- Peraturan Peminjaman ---
            [
                'category' => 'peraturan',
                'title'    => 'Syarat dan Ketentuan Peminjaman Buku',
                'content'  => 'Peminjaman buku hanya untuk siswa, guru, dan staf SMAMDA yang memiliki kartu anggota perpustakaan aktif. Setiap anggota dapat meminjam maksimal 2 buku sekaligus. Buku dipinjam selama 7 hari dan dapat diperpanjang 1 kali selama 7 hari tambahan jika tidak ada antrian peminjam lain.',
            ],
            [
                'category' => 'peraturan',
                'title'    => 'Tata Tertib di Ruang Perpustakaan',
                'content'  => 'Pengunjung perpustakaan wajib: (1) Menjaga ketenangan dan tidak membuat keributan. (2) Tidak membawa makanan dan minuman ke dalam ruang baca. (3) Mengembalikan buku ke rak setelah selesai dibaca jika tidak dipinjam. (4) Menjaga kebersihan ruangan. (5) Mematikan atau mengsilentkan ponsel. Pelanggaran dapat berakibat pada pencabutan hak pinjam sementara.',
            ],
            [
                'category' => 'peraturan',
                'title'    => 'Prosedur Peminjaman Buku',
                'content'  => 'Prosedur meminjam buku: (1) Cari buku yang diinginkan menggunakan katalog atau sistem pencarian. (2) Ambil buku dari rak sesuai nomor panggil. (3) Bawa buku ke meja sirkulasi bersama kartu anggota. (4) Petugas akan mencatat peminjaman. (5) Buku siap dibawa pulang. Jika buku sedang dipinjam, siswa dapat mendaftarkan antrian kepada petugas.',
            ],
            [
                'category' => 'peraturan',
                'title'    => 'Prosedur Pengembalian Buku',
                'content'  => 'Prosedur mengembalikan buku: (1) Bawa buku yang dipinjam ke meja sirkulasi sebelum atau pada tanggal jatuh tempo. (2) Serahkan buku kepada petugas bersama kartu anggota. (3) Petugas akan memeriksa kondisi buku dan mencatat pengembalian. (4) Pastikan mendapat konfirmasi bahwa buku telah dikembalikan.',
            ],

            // --- Denda ---
            [
                'category' => 'denda',
                'title'    => 'Denda Keterlambatan Pengembalian',
                'content'  => 'Keterlambatan pengembalian buku dikenakan denda sebesar Rp 500 per buku per hari keterlambatan. Contoh: terlambat 3 hari dengan 2 buku = Rp 500 × 3 × 2 = Rp 3.000. Denda harus dilunasi sebelum anggota dapat meminjam buku kembali. Denda dibayarkan langsung kepada petugas perpustakaan.',
            ],
            [
                'category' => 'denda',
                'title'    => 'Ketentuan Buku Hilang atau Rusak',
                'content'  => 'Jika buku yang dipinjam hilang atau rusak karena kelalaian peminjam: (1) Peminjam wajib mengganti buku dengan judul dan edisi yang sama. (2) Jika buku sudah tidak tersedia di pasaran, peminjam mengganti dengan buku lain senilai harga buku yang hilang/rusak. (3) Kerusakan ringan (halaman kotor, lecet) dikenakan denda perbaikan sesuai keputusan petugas.',
            ],

            // --- Kontak & Lokasi ---
            [
                'category' => 'kontak',
                'title'    => 'Kontak dan Lokasi Perpustakaan',
                'content'  => 'Perpustakaan SMAMDA berlokasi di lantai 2 Gedung Utama SMA Muhammadiyah 2 Surabaya. Untuk informasi lebih lanjut, hubungi petugas perpustakaan di meja sirkulasi atau melalui nomor sekolah. Email sekolah: info@smamda.sch.id. Petugas siap membantu pencarian buku, pendaftaran anggota, dan pertanyaan lainnya.',
            ],

            // --- Fasilitas ---
            [
                'category' => 'fasilitas',
                'title'    => 'Fasilitas yang Tersedia di Perpustakaan',
                'content'  => 'Perpustakaan SMAMDA menyediakan fasilitas: (1) Ruang baca yang nyaman dengan kursi dan meja. (2) Koleksi buku teks pelajaran, novel, ensiklopedia, dan referensi. (3) Akses komputer untuk pencarian katalog. (4) Wifi perpustakaan untuk keperluan belajar. (5) Papan pengumuman informasi perpustakaan. (6) Loker penitipan tas di pintu masuk.',
            ],
            [
                'category' => 'fasilitas',
                'title'    => 'Koleksi Perpustakaan',
                'content'  => 'Perpustakaan SMAMDA memiliki koleksi lebih dari 3.000 judul buku meliputi: buku pelajaran kurikulum Merdeka, buku referensi, novel dan sastra Indonesia dan terjemahan, buku pengembangan diri, majalah ilmiah, dan kamus. Koleksi diperbarui setiap tahun ajaran berdasarkan kebutuhan kurikulum dan usulan dari siswa maupun guru.',
            ],

            // --- Keanggotaan ---
            [
                'category' => 'keanggotaan',
                'title'    => 'Cara Mendaftar Keanggotaan Perpustakaan',
                'content'  => 'Pendaftaran anggota perpustakaan dilakukan di awal tahun ajaran untuk siswa baru. Syarat pendaftaran: (1) Mengisi formulir keanggotaan di meja petugas. (2) Menyerahkan fotokopi kartu pelajar. (3) Foto 3x4 sebanyak 1 lembar. Kartu anggota akan diterbitkan dalam 2–3 hari kerja. Keanggotaan berlaku selama siswa aktif di SMAMDA.',
            ],
        ];

        foreach ($infos as &$info) {
            $info['is_active']   = true;
            $info['created_at']  = now();
            $info['updated_at']  = now();
        }

        DB::table('library_info')->insert($infos);
        $this->command->info('✅ LibraryInfoSeeder: ' . count($infos) . ' dokumen info berhasil dimasukkan.');
    }
}