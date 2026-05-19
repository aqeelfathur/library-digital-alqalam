<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExploreController extends Controller
{
    public function __construct(
        private readonly SearchService $searchService
    ) {}

    public function index(Request $request): View
    {
        $filter = $this->ambilFilter($request);
        $totalBuku = Buku::count();
        $mulai  = microtime(true);
        $hasil  = $this->searchService->cari($filter);
        $durasi = round((microtime(true) - $mulai) * 1000, 2);

        $jumlahHasil  = $hasil->total();
        $filterAktif  = $this->searchService->filterAktif($filter);
        $adaFilter    = $this->searchService->adaFilter($filter);

        $kategori         = Kategori::withCount('buku')->orderBy('name')->get();
        $rekomendasiBuku  = $this->rekomendasiBuku($filter);

        $judulHalaman = $this->judulHalaman($filter);

        return view('publik.explore', compact(
            'hasil',
            'filter',
            'filterAktif',
            'adaFilter',
            'jumlahHasil',
            'durasi',
            'kategori',
            'rekomendasiBuku',
            'judulHalaman',
            'totalBuku'
        ));
    }

    /**
     * Ambil dan sanitasi semua parameter filter dari request.
     */
    private function ambilFilter(Request $request): array
    {
        return [
            'search'       => $request->string('search')->toString(),
            'judul'        => $request->string('judul')->toString(),
            'pengarang'    => $request->string('pengarang')->toString(),
            'subjek'       => $request->string('subjek')->toString(),
            'isbn'         => $request->string('isbn')->toString(),
            'penerbit'     => $request->string('penerbit')->toString(),
            'kategori'     => $request->string('kategori')->toString(),
            'tipe_koleksi' => $request->string('tipe_koleksi')->toString(),
            'gmd'          => $request->string('gmd')->toString(),
            'lokasi'       => $request->string('lokasi')->toString(),
            'tahun_dari'   => $request->string('tahun_dari')->toString(),
            'tahun_sampai' => $request->string('tahun_sampai')->toString(),
            'urutan'       => $request->string('urutan', 'terbaru')->toString(),
        ];
    }

    /**
     * Ambil buku rekomendasi untuk sidebar (populer, tidak termasuk hasil utama).
     */
    private function rekomendasiBuku(array $filter): \Illuminate\Database\Eloquent\Collection
    {
        return Buku::query()
            ->with('kategori')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->limit(8)
            ->get();
    }

    /**
     * Generate judul halaman dinamis berdasarkan filter aktif.
     */
    private function judulHalaman(array $filter): string
    {
        if (filled($filter['search'])) {
            return "Hasil pencarian: \"{$filter['search']}\"";
        }

        if (filled($filter['kategori'])) {
            $kategori = \App\Models\Kategori::where('slug', $filter['kategori'])->first();
            return 'Kategori: ' . ($kategori?->name ?? $filter['kategori']);
        }

        if (filled($filter['pengarang'])) {
            return "Karya: {$filter['pengarang']}";
        }

        return 'Jelajahi Koleksi';
    }
}