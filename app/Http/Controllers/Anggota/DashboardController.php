<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $anggota = auth()->user()->load([
            'peminjaman' => fn ($q) => $q->with('buku.kategori')
                ->orderByDesc('created_at')
                ->limit(10),
        ]);

        $peminjamanAktif  = $anggota->peminjamanAktif();
        $riwayatPeminjaman = $anggota->peminjaman()
            ->with('buku.kategori')
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('anggota.dashboard', compact('anggota', 'peminjamanAktif', 'riwayatPeminjaman'));
    }
}