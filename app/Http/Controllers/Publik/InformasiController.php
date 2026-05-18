<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\InformasiPerpustakaan;
use App\Models\Kategori;
use Illuminate\View\View;

class InformasiController extends Controller
{
    public function index(): View
    {
        $informasi  = InformasiPerpustakaan::ambil();
        $totalBuku  = Buku::count();
        $totalKategori = Kategori::count();

        return view('publik.informasi', compact('informasi', 'totalBuku', 'totalKategori'));
    }
}