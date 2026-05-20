<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\InformasiPerpustakaan;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $kategori = Kategori::withCount('buku')->get();

        $bukuPopuler = Buku::query()
            ->with('kategori')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->limit(12)
            ->get();

        $bukuTerbaru = Buku::query()
            ->with('kategori')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $penikmatKoleksi = User::query()
            ->withCount(['peminjaman' => fn ($q) => $q->whereYear('created_at', now()->year)])
            ->orderByDesc('peminjaman_count')
            ->limit(3)
            ->get();

        $informasi = InformasiPerpustakaan::ambil();

        // Top 6 Buku Rekomendasi — paling banyak dipinjam sepanjang waktu
        // Hanya buku yang pernah dipinjam minimal 1x yang masuk daftar.
        // Fallback: jika data peminjaman belum cukup 6, isi sisa slot
        // dengan buku terbaru agar section tidak tampil setengah kosong.
        $bukuRekomendasi = Buku::query()
            ->with('kategori')
            ->withCount('peminjaman')
            ->having('peminjaman_count', '>', 0)
            ->orderByDesc('peminjaman_count')
            ->limit(6)
            ->get();

        if ($bukuRekomendasi->count() < 6) {
            $idSudahAda = $bukuRekomendasi->pluck('id');

            $fallback = Buku::query()
                ->with('kategori')
                ->withCount('peminjaman')
                ->whereNotIn('id', $idSudahAda)
                ->orderByDesc('created_at')
                ->limit(6 - $bukuRekomendasi->count())
                ->get();

            $bukuRekomendasi = $bukuRekomendasi->concat($fallback);
        }

        return view('publik.beranda', compact(
            'kategori',
            'bukuPopuler',
            'bukuTerbaru',
            'penikmatKoleksi',
            'informasi',
            'bukuRekomendasi',
        ));
    }
}