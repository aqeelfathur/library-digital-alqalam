<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistik = [
            'total_buku'           => Buku::count(),
            'total_anggota'        => User::anggota()->count(),
            'peminjaman_aktif'     => Peminjaman::whereIn('status', ['pending', 'borrowed'])->count(),
            'peminjaman_hari_ini'  => Peminjaman::whereDate('created_at', today())->count(),
            'dikembalikan_bulan_ini' => Peminjaman::where('status', 'returned')
                ->whereMonth('returned_at', now()->month)->count(),
            'buku_tersedia'        => Buku::tersedia()->count(),
        ];

        $peminjamanTerbaru = Peminjaman::query()
            ->with(['anggota', 'buku'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $logAktivitas = LogAktivitas::query()
            ->with('pengguna')
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        $grafikPeminjaman = $this->dataGrafikPeminjaman();

        return view('pustakawan.dashboard', compact(
            'statistik',
            'peminjamanTerbaru',
            'logAktivitas',
            'grafikPeminjaman'
        ));
    }

    private function dataGrafikPeminjaman(): array
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $data[] = [
                'bulan'  => $bulan->translatedFormat('M Y'),
                'jumlah' => Peminjaman::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->count(),
            ];
        }
        return $data;
    }
}
