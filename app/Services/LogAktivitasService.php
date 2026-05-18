<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class LogAktivitasService
{
    public function catat(string $aksi, string $deskripsi, ?int $userId = null): void
    {
        LogAktivitas::create([
            'user_id'     => $userId ?? Auth::id(),
            'action'      => $aksi,
            'description' => $deskripsi,
            'created_at'  => now(),
        ]);
    }

    public function catatLogin(int $userId): void
    {
        $this->catat('login', 'Pengguna masuk ke sistem', $userId);
    }

    public function catatLogout(int $userId): void
    {
        $this->catat('logout', 'Pengguna keluar dari sistem', $userId);
    }

    public function catatPeminjaman(int $userId, string $judulBuku): void
    {
        $this->catat('peminjaman', "Mengajukan peminjaman buku: {$judulBuku}", $userId);
    }

    public function catatPersetujuan(int $pustakawanId, string $judulBuku, string $namaAnggota): void
    {
        $this->catat('persetujuan', "Menyetujui peminjaman buku '{$judulBuku}' oleh {$namaAnggota}", $pustakawanId);
    }

    public function catatPenolakan(int $pustakawanId, string $judulBuku, string $namaAnggota): void
    {
        $this->catat('penolakan', "Menolak peminjaman buku '{$judulBuku}' oleh {$namaAnggota}", $pustakawanId);
    }

    public function catatPengembalian(int $pustakawanId, string $judulBuku, string $namaAnggota): void
    {
        $this->catat('pengembalian', "Mencatat pengembalian buku '{$judulBuku}' oleh {$namaAnggota}", $pustakawanId);
    }
}