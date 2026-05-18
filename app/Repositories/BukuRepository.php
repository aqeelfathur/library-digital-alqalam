<?php

namespace App\Repositories;

use App\Models\Buku;
use App\Repositories\Contracts\BukuRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BukuRepository implements BukuRepositoryInterface
{
    public function semuaDenganFilter(array $filter): LengthAwarePaginator
    {
        return Buku::query()
            ->with('kategori')
            ->when($filter['cari'] ?? null, fn ($q, $kata) => $q->cari($kata))
            ->when($filter['kategori'] ?? null, fn ($q, $id) => $q->where('category_id', $id))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();
    }

    public function populer(int $limit = 12): \Illuminate\Database\Eloquent\Collection
    {
        return Buku::query()
            ->with('kategori')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->limit($limit)
            ->get();
    }

    public function terbaru(int $limit = 12): \Illuminate\Database\Eloquent\Collection
    {
        return Buku::query()
            ->with('kategori')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function cariDenganKategori(string $kata, ?int $kategoriId): LengthAwarePaginator
    {
        return Buku::query()
            ->with('kategori')
            ->cari($kata)
            ->when($kategoriId, fn ($q, $id) => $q->where('category_id', $id))
            ->paginate(12)
            ->withQueryString();
    }
}