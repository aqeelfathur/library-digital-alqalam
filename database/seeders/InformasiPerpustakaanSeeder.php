<?php

namespace Database\Seeders;

use App\Models\InformasiPerpustakaan;
use Illuminate\Database\Seeder;

class InformasiPerpustakaanSeeder extends Seeder
{
    public function run(): void
    {
        InformasiPerpustakaan::create([
            'address'                => 'Jl. Pucang Anom No.91, Kertajaya, Kec. Gubeng, Surabaya, Jawa Timur 60282',
            'phone'                  => '(031) 5021316',
            'email'                  => 'admin@smamda.net',
            'operational_hours'      => "Senin - Kamis  : 07.00 - 15.30 WIB\nJumat            : 07.00 - 15.00 WIB\nSabtu            : 07.00 - 12.00 WIB\nMinggu & Libur   : Tutup",
            'membership_information' => "Keanggotaan perpustakaan terbuka bagi seluruh civitas akademika SMA Muhammadiyah 2 Surabaya.\n\nSyarat Keanggotaan:\n- Siswa/siswi aktif SMAMDA\n- Memiliki kartu pelajar yang masih berlaku\n- Mendaftar dapat melalui petugas pustakawan secara langsung\n\nHak Anggota:\n- Meminjam 1 (satu) buku dalam satu waktu\n- Durasi peminjaman 7 hari kalender\n- Konsultasi referensi dengan pustakawan\n- Akses ruang baca dan ruang diskusi\n\nKewajiban Anggota:\n- Menjaga kondisi buku yang dipinjam\n- Mengembalikan buku tepat waktu\n- Melaporkan kerusakan atau kehilangan buku kepada pustakawan\n- Mematuhi tata tertib perpustakaan",
            'maps_embed_url'         => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.619916013971!2d112.75666487454617!3d-7.284013571586942!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbca5a923a1d%3A0xc94957f0916bede2!2sSMA%20Muhammadiyah%202%20Surabaya!5e0!3m2!1sid!2sid!4v1779179747236!5m2!1sid!2sid"',
        ]);

        $this->command->info('Seeder InformasiPerpustakaan selesai.');
    }
}