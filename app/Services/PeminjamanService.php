<?php

namespace App\Services;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanService
{
    public function __construct(
        private readonly LogAktivitasService $logService
    ) {}

    public function ajukanPeminjaman(User $anggota, Buku $buku): Peminjaman
    {
        $this->validasiDapatMeminjam($anggota, $buku);

        return DB::transaction(function () use ($anggota, $buku) {
            $peminjaman = Peminjaman::create([
                'user_id' => $anggota->id,
                'book_id' => $buku->id,
                'status'  => 'pending',
            ]);

            $this->logService->catatPeminjaman($anggota->id, $buku->title);

            return $peminjaman;
        });
    }

    public function setujuiPeminjaman(Peminjaman $peminjaman, User $pustakawan): Peminjaman
    {
        if ($peminjaman->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Peminjaman ini tidak dapat disetujui.',
            ]);
        }

        return DB::transaction(function () use ($peminjaman, $pustakawan) {
            $peminjaman->update([
                'status'       => 'borrowed',
                'processed_by' => $pustakawan->id,
                'borrowed_at'  => now(),
                'due_date'     => now()->addDays(7),
            ]);

            $peminjaman->buku()->update(['status' => 'dipinjam']);

            $this->logService->catatPersetujuan(
                $pustakawan->id,
                $peminjaman->buku->title,
                $peminjaman->anggota->name
            );

            return $peminjaman->refresh();
        });
    }

    public function tolakPeminjaman(Peminjaman $peminjaman, User $pustakawan): Peminjaman
    {
        if ($peminjaman->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Peminjaman ini tidak dapat ditolak.',
            ]);
        }

        return DB::transaction(function () use ($peminjaman, $pustakawan) {
            $peminjaman->update([
                'status'       => 'rejected',
                'processed_by' => $pustakawan->id,
            ]);

            $this->logService->catatPenolakan(
                $pustakawan->id,
                $peminjaman->buku->title,
                $peminjaman->anggota->name
            );

            return $peminjaman->refresh();
        });
    }

    public function prosesPengembalian(Peminjaman $peminjaman, User $pustakawan): Peminjaman
    {
        if ($peminjaman->status !== 'borrowed') {
            throw ValidationException::withMessages([
                'status' => 'Peminjaman ini tidak dapat dikembalikan.',
            ]);
        }

        return DB::transaction(function () use ($peminjaman, $pustakawan) {
            $statusBaru = $peminjaman->terlambat() ? 'late' : 'returned';

            $peminjaman->update([
                'status'      => $statusBaru,
                'returned_at' => now(),
            ]);

            $peminjaman->buku()->update(['status' => 'tersedia']);

            $this->logService->catatPengembalian(
                $pustakawan->id,
                $peminjaman->buku->title,
                $peminjaman->anggota->name
            );

            return $peminjaman->refresh();
        });
    }

    private function validasiDapatMeminjam(User $anggota, Buku $buku): void
    {
        if ($anggota->sedangMeminjam()) {
            throw ValidationException::withMessages([
                'buku' => 'Anda masih memiliki peminjaman aktif. Kembalikan buku terlebih dahulu.',
            ]);
        }

        if (!$buku->isTersedia()) {
            throw ValidationException::withMessages([
                'buku' => 'Buku ini sedang tidak tersedia untuk dipinjam.',
            ]);
        }

        if (!$anggota->isAktif()) {
            throw ValidationException::withMessages([
                'buku' => 'Akun Anda tidak aktif. Hubungi pustakawan.',
            ]);
        }
    }
}