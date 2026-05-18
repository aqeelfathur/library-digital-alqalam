<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
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
        $buku->load('kategori');
        $sedangMeminjam = auth()->user()->sedangMeminjam();

        return view('anggota.buku.show', compact('buku', 'sedangMeminjam'));
    }
}