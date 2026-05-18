<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use App\Services\LogAktivitasService;
use Illuminate\Console\Command;

class CekPeminjamanTerlambat extends Command
{
    protected $signature   = 'perpustakaan:cek-terlambat';
    protected $description = 'Memperbarui status peminjaman yang telah melewati batas waktu pengembalian';

    public function __construct(
        private readonly LogAktivitasService $logService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $peminjamanTerlambat = Peminjaman::query()
            ->where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('returned_at')
            ->get();

        if ($peminjamanTerlambat->isEmpty()) {
            $this->info('Tidak ada peminjaman yang terlambat.');
            return self::SUCCESS;
        }

        $jumlah = 0;
        foreach ($peminjamanTerlambat as $peminjaman) {
            $peminjaman->update(['status' => 'late']);
            $this->logService->catat(
                'terlambat',
                "Peminjaman buku '{$peminjaman->buku->title}' oleh {$peminjaman->anggota->name} ditandai terlambat.",
                null
            );
            $jumlah++;
        }

        $this->info("Berhasil memperbarui {$jumlah} peminjaman terlambat.");
        return self::SUCCESS;
    }
}