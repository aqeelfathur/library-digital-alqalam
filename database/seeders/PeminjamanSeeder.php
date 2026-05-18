<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $anggota    = User::anggota()->aktif()->get();
        $pustakawan = User::pustakawan()->first();

        if ($anggota->isEmpty() || !$pustakawan) {
            $this->command->warn('Seeder Peminjaman dilewati: tidak ada anggota atau pustakawan.');
            return;
        }

        $jumlahDibuat = 0;

        // ── Riwayat peminjaman sudah dikembalikan ────────────────────
        foreach ($anggota->take(20) as $angg) {
            $bukuAcak = Buku::inRandomOrder()->take(rand(2, 4))->get();

            foreach ($bukuAcak as $buku) {
                $tanggalPinjam = now()->subDays(rand(30, 365));
                $tanggalKembali = $tanggalPinjam->copy()->addDays(rand(3, 9));

                Peminjaman::create([
                    'user_id'      => $angg->id,
                    'book_id'      => $buku->id,
                    'processed_by' => $pustakawan->id,
                    'status'       => 'returned',
                    'borrowed_at'  => $tanggalPinjam,
                    'due_date'     => $tanggalPinjam->copy()->addDays(7),
                    'returned_at'  => $tanggalKembali,
                    'created_at'   => $tanggalPinjam,
                    'updated_at'   => $tanggalKembali,
                ]);

                $jumlahDibuat++;
            }
        }

        // ── Riwayat terlambat ────────────────────────────────────────
        foreach ($anggota->slice(5, 3) as $angg) {
            $bukuAcak = Buku::inRandomOrder()->first();

            if ($bukuAcak) {
                $tanggalPinjam  = now()->subDays(rand(20, 60));
                $tanggalKembali = $tanggalPinjam->copy()->addDays(rand(10, 20));

                Peminjaman::create([
                    'user_id'      => $angg->id,
                    'book_id'      => $bukuAcak->id,
                    'processed_by' => $pustakawan->id,
                    'status'       => 'late',
                    'borrowed_at'  => $tanggalPinjam,
                    'due_date'     => $tanggalPinjam->copy()->addDays(7),
                    'returned_at'  => $tanggalKembali,
                    'created_at'   => $tanggalPinjam,
                    'updated_at'   => $tanggalKembali,
                ]);

                $jumlahDibuat++;
            }
        }

        // ── Peminjaman aktif (status borrowed) ───────────────────────
        foreach ($anggota->slice(0, 5) as $angg) {
            if ($angg->sedangMeminjam()) {
                continue;
            }

            $bukuTersedia = Buku::tersedia()->inRandomOrder()->first();

            if ($bukuTersedia) {
                $tanggalPinjam = now()->subDays(rand(1, 5));

                Peminjaman::create([
                    'user_id'      => $angg->id,
                    'book_id'      => $bukuTersedia->id,
                    'processed_by' => $pustakawan->id,
                    'status'       => 'borrowed',
                    'borrowed_at'  => $tanggalPinjam,
                    'due_date'     => $tanggalPinjam->copy()->addDays(7),
                    'returned_at'  => null,
                    'created_at'   => $tanggalPinjam,
                    'updated_at'   => $tanggalPinjam,
                ]);

                // Update status buku menjadi dipinjam
                $bukuTersedia->update(['status' => 'dipinjam']);

                $jumlahDibuat++;
            }
        }

        // ── Peminjaman pending (menunggu persetujuan) ────────────────
        foreach ($anggota->slice(8, 4) as $angg) {
            if ($angg->sedangMeminjam()) {
                continue;
            }

            $bukuTersedia = Buku::tersedia()->inRandomOrder()->first();

            if ($bukuTersedia) {
                Peminjaman::create([
                    'user_id'    => $angg->id,
                    'book_id'    => $bukuTersedia->id,
                    'status'     => 'pending',
                    'created_at' => now()->subHours(rand(1, 48)),
                    'updated_at' => now()->subHours(rand(1, 48)),
                ]);

                $jumlahDibuat++;
            }
        }

        // ── Peminjaman ditolak ───────────────────────────────────────
        foreach ($anggota->slice(15, 3) as $angg) {
            $bukuAcak = Buku::inRandomOrder()->first();

            if ($bukuAcak) {
                $tanggal = now()->subDays(rand(5, 15));

                Peminjaman::create([
                    'user_id'      => $angg->id,
                    'book_id'      => $bukuAcak->id,
                    'processed_by' => $pustakawan->id,
                    'status'       => 'rejected',
                    'created_at'   => $tanggal,
                    'updated_at'   => $tanggal->copy()->addHours(rand(1, 12)),
                ]);

                $jumlahDibuat++;
            }
        }

        $this->command->info("Seeder Peminjaman selesai: {$jumlahDibuat} transaksi peminjaman dibuat.");
    }
}