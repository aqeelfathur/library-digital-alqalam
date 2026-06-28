<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function show(Buku $buku): View
    {
        $buku->load([
            'kategori',
            'ulasan' => fn ($query) => $query->with('user')->latest(),
        ])->loadCount('ulasan')->loadAvg('ulasan', 'rating');

        return view('publik.buku.show', compact('buku'));
    }
}
