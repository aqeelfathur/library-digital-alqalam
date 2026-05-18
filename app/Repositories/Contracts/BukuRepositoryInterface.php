<?php

namespace App\Repositories\Contracts;

use App\Models\Buku;
use Illuminate\Pagination\LengthAwarePaginator;

interface BukuRepositoryInterface
{
    public function semuaDenganFilter(array $filter): LengthAwarePaginator;
    public function populer(int $limit): \Illuminate\Database\Eloquent\Collection;
    public function terbaru(int $limit): \Illuminate\Database\Eloquent\Collection;
    public function cariDenganKategori(string $kata, ?int $kategoriId): LengthAwarePaginator;
}