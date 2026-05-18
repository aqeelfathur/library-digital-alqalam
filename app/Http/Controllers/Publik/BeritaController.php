<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $berita = Berita::query()
            ->terbit()
            ->cari($request->string('cari'))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('publik.berita.index', compact('berita'));
    }

    public function show(Berita $berita): View
    {
        abort_unless($berita->published_at && $berita->published_at->isPast(), 404);

        $beritaLainnya = Berita::query()
            ->terbit()
            ->where('id', '!=', $berita->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('publik.berita.show', compact('berita', 'beritaLainnya'));
    }
}