<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buku\StoreUlasanBukuRequest;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $buku = Buku::query()
            ->with('kategori')
            ->when($request->filled('cari'), fn ($q) => $q->cari($request->string('cari')))
            ->when($request->filled('kategori'), fn ($q) => $q->where('category_id', $request->integer('kategori')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $kategori = Kategori::orderBy('name')->get();

        return view('anggota.buku.index', compact('buku', 'kategori'));
    }

    public function show(Buku $buku): View
    {
        $buku->load([
            'kategori',
            'ulasan' => fn ($query) => $query->with('user')->latest(),
        ])->loadCount('ulasan')->loadAvg('ulasan', 'rating');

        $sedangMeminjam = auth()->user()->sedangMeminjam();
        $ulasanSaya = $buku->ulasan
            ->firstWhere('user_id', auth()->id());

        return view('anggota.buku.show', compact('buku', 'sedangMeminjam', 'ulasanSaya'));
    }

    public function simpanUlasan(StoreUlasanBukuRequest $request, Buku $buku): RedirectResponse
    {
        $buku->ulasan()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->validated()
        );

        return back()->with('sukses', 'Ulasan buku berhasil disimpan.');
    }
}
