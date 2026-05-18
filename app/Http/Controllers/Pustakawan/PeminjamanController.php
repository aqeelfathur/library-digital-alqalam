<?php

namespace App\Http\Controllers\Pustakawan;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(
        private readonly PeminjamanService $peminjamanService
    ) {}

    public function index(Request $request): View
    {
        $peminjaman = Peminjaman::query()
            ->with(['anggota', 'buku.kategori', 'pustakawan'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('cari'), function ($q) use ($request) {
                $kata = $request->string('cari');
                $q->whereHas('anggota', fn ($a) => $a->where('name', 'like', "%{$kata}%"))
                  ->orWhereHas('buku', fn ($b) => $b->where('title', 'like', "%{$kata}%"));
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('pustakawan.peminjaman.index', compact('peminjaman'));
    }

    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        try {
            $this->peminjamanService->setujuiPeminjaman($peminjaman, auth()->user());
            return back()->with('sukses', 'Peminjaman berhasil disetujui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    public function tolak(Peminjaman $peminjaman): RedirectResponse
    {
        try {
            $this->peminjamanService->tolakPeminjaman($peminjaman, auth()->user());
            return back()->with('sukses', 'Peminjaman berhasil ditolak.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        try {
            $this->peminjamanService->prosesPengembalian($peminjaman, auth()->user());
            return back()->with('sukses', 'Buku berhasil dicatat sebagai dikembalikan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}