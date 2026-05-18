<?php

namespace Database\Seeders;

use App\Models\InformasiPerpustakaan;
use Illuminate\Database\Seeder;

class InformasiPerpustakaanSeeder extends Seeder
{
    public function run(): void
    {
        InformasiPerpustakaan::create([
            'address'                => 'Jl. Pendidikan No. 1, Kelurahan Contoh, Kecamatan Contoh, Kota Surabaya, Jawa Timur 60111',
            'phone'                  => '+62 31-5555-1234',
            'email'                  => 'perpustakaan@alqalam.sch.id',
            'operational_hours'      => "Senin - Kamis  : 07.30 - 16.00 WIB\nJumat            : 07.30 - 11.30 WIB\nSabtu            : 08.00 - 12.00 WIB\nMinggu & Libur   : Tutup",
            'membership_information' => "Keanggotaan perpustakaan terbuka bagi seluruh civitas akademika sekolah Al-Qalam.\n\nSyarat Keanggotaan:\n- Siswa/siswi aktif sekolah Al-Qalam\n- Memiliki kartu pelajar yang masih berlaku\n- Mendaftar dapat melalui petugas pustakawan secara langsung\n\nHak Anggota:\n- Meminjam 1 (satu) buku dalam satu waktu\n- Durasi peminjaman 7 hari kalender\n- Konsultasi referensi dengan pustakawan\n- Akses ruang baca dan ruang diskusi\n\nKewajiban Anggota:\n- Menjaga kondisi buku yang dipinjam\n- Mengembalikan buku tepat waktu\n- Melaporkan kerusakan atau kehilangan buku kepada pustakawan\n- Mematuhi tata tertib perpustakaan",
            'maps_embed_url'         => '',
        ]);

        $this->command->info('Seeder InformasiPerpustakaan selesai.');
    }
}