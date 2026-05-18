<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\InformasiPerpustakaan;
use App\Models\Kategori;
use App\Models\Peminjaman;
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

        return view('publik.beranda', compact(
            'kategori',
            'bukuPopuler',
            'bukuTerbaru',
            'penikmatKoleksi',
            'informasi'
        ));
    }
}