<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Services\PeminjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(
        private readonly PeminjamanService $peminjamanService
    ) {}

    public function index(): View
    {
        $peminjaman = auth()->user()->peminjaman()
            ->with('buku.kategori')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('anggota.peminjaman.index', compact('peminjaman'));
    }

    public function ajukan(Buku $buku): RedirectResponse
    {
        try {
            $this->peminjamanService->ajukanPeminjaman(auth()->user(), $buku);

            return redirect()->route('anggota.peminjaman.index')
                ->with('sukses', 'Permintaan peminjaman berhasil diajukan. Tunggu konfirmasi pustakawan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->with('gagal', $e->getMessage());
        }
    }
}