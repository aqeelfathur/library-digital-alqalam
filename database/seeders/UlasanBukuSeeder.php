<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\UlasanBuku;
use App\Models\User;
use Illuminate\Database\Seeder;

class UlasanBukuSeeder extends Seeder
{
    public function run(): void
    {
        $anggota = User::anggota()->aktif()->limit(12)->get();
        $buku = Buku::orderBy('id')->limit(15)->get();

        if ($anggota->isEmpty() || $buku->isEmpty()) {
            return;
        }

        $komentar = [
            'Bukunya mudah dipahami dan cocok untuk referensi belajar.',
            'Isi bukunya menarik, bahasanya ringan, dan banyak bagian yang membantu.',
            'Materinya cukup lengkap untuk menambah wawasan.',
            'Saya suka alur pembahasannya karena runtut dan tidak membingungkan.',
            'Buku ini bagus untuk dibaca ulang saat membutuhkan rujukan cepat.',
            'Beberapa bagian sangat relevan dengan tugas sekolah.',
            'Penjelasannya jelas, walau ada bagian yang perlu dibaca pelan-pelan.',
            'Sampul dan koleksinya terawat, isi bukunya juga masih nyaman dibaca.',
        ];

        foreach ($buku as $index => $item) {
            $pengulas = $anggota->shuffle()->take(($index % 4) + 1);

            foreach ($pengulas as $urutan => $user) {
                UlasanBuku::updateOrCreate(
                    [
                        'book_id' => $item->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'rating' => (($index + $urutan) % 5) + 1,
                        'comment' => $komentar[($index + $urutan) % count($komentar)],
                    ]
                );
            }
        }

        $this->command->info('Seeder Ulasan Buku selesai: ' . UlasanBuku::count() . ' ulasan tersedia.');
    }
}
