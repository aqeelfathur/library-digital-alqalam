<?php

namespace App\Services;

use App\Models\Buku;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SearchService
{
    private const PER_HALAMAN = 10;

    /**
     * Jalankan pipeline pencarian lengkap berdasarkan parameter filter.
     */
    public function cari(array $filter): LengthAwarePaginator
    {
        $query = Buku::query()->with('kategori');

        $query = $this->terapkanFilter($query, $filter);
        $query = $this->terapkanUrutan($query, $filter['urutan'] ?? 'terbaru');

        return $query->paginate(self::PER_HALAMAN)->withQueryString();
    }

    /**
     * Hitung total hasil tanpa pagination (untuk statistik).
     */
    public function hitungHasil(array $filter): int
    {
        $query = Buku::query();
        return $this->terapkanFilter($query, $filter)->count();
    }

    /**
     * Ambil filter aktif untuk ditampilkan sebagai badge.
     */
    public function filterAktif(array $filter): Collection
    {
        $labelFilter = [
            'search'          => 'Kata Kunci',
            'judul'           => 'Judul',
            'pengarang'       => 'Pengarang',
            'subjek'          => 'Subjek',
            'isbn'            => 'ISBN/ISSN',
            'penerbit'        => 'Penerbit',
            'kategori'        => 'Kategori',
            'tipe_koleksi'    => 'Tipe Koleksi',
            'gmd'             => 'GMD/Media',
            'lokasi'          => 'Lokasi',
            'tahun_dari'      => 'Tahun Dari',
            'tahun_sampai'    => 'Tahun Sampai',
        ];

        return collect($filter)
            ->only(array_keys($labelFilter))
            ->filter(fn ($nilai) => filled($nilai))
            ->map(fn ($nilai, $kunci) => [
                'kunci'  => $kunci,
                'label'  => $labelFilter[$kunci],
                'nilai'  => $nilai,
            ]);
    }

    /**
     * Cek apakah ada filter aktif.
     */
    public function adaFilter(array $filter): bool
    {
        return $this->filterAktif($filter)->isNotEmpty();
    }

    /**
     * Terapkan semua filter ke query.
     */
    private function terapkanFilter(Builder $query, array $filter): Builder
    {
        // Pencarian umum (search bar utama)
        if (filled($filter['search'] ?? null)) {
            $query->cari($filter['search']);
        }

        // Pencarian spesifik (advanced search)
        if (filled($filter['judul'] ?? null)) {
            $query->filterJudul($filter['judul']);
        }

        if (filled($filter['pengarang'] ?? null)) {
            $query->filterPenulis($filter['pengarang']);
        }

        if (filled($filter['subjek'] ?? null)) {
            $query->filterSubjek($filter['subjek']);
        }

        if (filled($filter['isbn'] ?? null)) {
            $query->filterIsbn($filter['isbn']);
        }

        if (filled($filter['penerbit'] ?? null)) {
            $query->filterPenerbit($filter['penerbit']);
        }

        if (filled($filter['kategori'] ?? null)) {
            $query->filterKategori($filter['kategori']);
        }

        if (filled($filter['tipe_koleksi'] ?? null) && $filter['tipe_koleksi'] !== 'semua') {
            $query->filterTipeKoleksi($filter['tipe_koleksi']);
        }

        if (filled($filter['gmd'] ?? null) && $filter['gmd'] !== 'semua') {
            $query->filterGmd($filter['gmd']);
        }

        if (filled($filter['lokasi'] ?? null) && $filter['lokasi'] !== 'semua') {
            $query->filterLokasi($filter['lokasi']);
        }

        if (filled($filter['tahun_dari'] ?? null) || filled($filter['tahun_sampai'] ?? null)) {
            $query->filterTahun($filter['tahun_dari'] ?? null, $filter['tahun_sampai'] ?? null);
        }

        return $query;
    }

    /**
     * Terapkan urutan/sorting ke query.
     */
    private function terapkanUrutan(Builder $query, string $urutan): Builder
    {
        return $query->urutkan($urutan);
    }
}