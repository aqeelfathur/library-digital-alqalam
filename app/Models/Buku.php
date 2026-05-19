<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'category_id',
        'title',
        'series_title',
        'call_number',
        'publisher',
        'physical_description',
        'language',
        'isbn_issn',
        'classification',
        'content_type',
        'media_type',
        'carrier_type',
        'edition',
        'publication_year',
        'location',
        'collection_type',
        'gmd_type',
        'subject',
        'specific_detail_info',
        'description',
        'author',
        'status',
        'image_url',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopePopuler($query)
    {
        return $query->withCount('peminjaman')->orderByDesc('peminjaman_count');
    }

    /**
     * Pencarian umum: judul, penulis, ISBN, subyek, penerbit.
     */
    public function scopeCari($query, string $kata)
    {
        return $query->where(function ($q) use ($kata) {
            $q->where('title', 'like', "%{$kata}%")
                ->orWhere('author', 'like', "%{$kata}%")
                ->orWhere('isbn_issn', 'like', "%{$kata}%")
                ->orWhere('subject', 'like', "%{$kata}%")
                ->orWhere('publisher', 'like', "%{$kata}%")
                ->orWhere('description', 'like', "%{$kata}%");
        });
    }

    /**
     * Filter berdasarkan judul spesifik.
     */
    public function scopeFilterJudul($query, string $judul)
    {
        return $query->where('title', 'like', "%{$judul}%");
    }

    /**
     * Filter berdasarkan penulis.
     */
    public function scopeFilterPenulis($query, string $penulis)
    {
        return $query->where('author', 'like', "%{$penulis}%");
    }

    /**
     * Filter berdasarkan subyek.
     */
    public function scopeFilterSubjek($query, string $subjek)
    {
        return $query->where('subject', 'like', "%{$subjek}%");
    }

    /**
     * Filter berdasarkan ISBN/ISSN.
     */
    public function scopeFilterIsbn($query, string $isbn)
    {
        return $query->where('isbn_issn', 'like', "%{$isbn}%");
    }

    /**
     * Filter berdasarkan penerbit.
     */
    public function scopeFilterPenerbit($query, string $penerbit)
    {
        return $query->where('publisher', 'like', "%{$penerbit}%");
    }

    /**
     * Filter berdasarkan kategori slug.
     */
    public function scopeFilterKategori($query, string $slug)
    {
        return $query->whereHas('kategori', fn ($q) => $q->where('slug', $slug));
    }

    /**
     * Filter berdasarkan tipe koleksi.
     */
    public function scopeFilterTipeKoleksi($query, string $tipe)
    {
        return $query->where('collection_type', $tipe);
    }

    /**
     * Filter berdasarkan GMD/media.
     */
    public function scopeFilterGmd($query, string $gmd)
    {
        return $query->where('gmd_type', $gmd);
    }

    /**
     * Filter berdasarkan lokasi rak.
     */
    public function scopeFilterLokasi($query, string $lokasi)
    {
        return $query->where('location', $lokasi);
    }

    /**
     * Filter berdasarkan rentang tahun terbit.
     */
    public function scopeFilterTahun($query, ?string $dari, ?string $sampai)
    {
        return $query
            ->when($dari, fn ($q) => $q->where('publication_year', '>=', $dari))
            ->when($sampai, fn ($q) => $q->where('publication_year', '<=', $sampai));
    }

    /**
     * Sorting dinamis.
     */
    public function scopeUrutkan($query, string $urutan)
    {
        return match ($urutan) {
            'terlama'       => $query->orderBy('created_at'),
            'populer'       => $query->withCount('peminjaman')->orderByDesc('peminjaman_count'),
            'az'            => $query->orderBy('title'),
            'za'            => $query->orderByDesc('title'),
            default         => $query->orderByDesc('created_at'), // terbaru
        };
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }

    public function sampulUrl(): string
    {
        return $this->image_url
            ? asset('storage/' . $this->image_url)
            : asset('images/default-book.png');
    }

    public function labelStatus(): string
    {
        return match ($this->status) {
            'tersedia'    => 'Tersedia',
            'dipinjam'    => 'Dipinjam',
            'maintenance' => 'Perawatan',
            'hilang'      => 'Hilang',
            default       => ucfirst($this->status),
        };
    }

    public function warnaStatus(): string
    {
        return match ($this->status) {
            'tersedia'    => 'green',
            'dipinjam'    => 'yellow',
            'maintenance' => 'orange',
            'hilang'      => 'red',
            default       => 'gray',
        };
    }

    public function labelTipeKoleksi(): string
    {
        return match ($this->collection_type) {
            'majalah'  => 'Majalah',
            'jurnal'   => 'Jurnal',
            'skripsi'  => 'Skripsi',
            'ebook'    => 'Ebook',
            default    => 'Buku',
        };
    }
}