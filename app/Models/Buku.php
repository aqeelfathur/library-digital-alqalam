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
        'subject',
        'specific_detail_info',
        'author',
        'status',
        'image_url',
    ];

    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }

    // Scopes
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeCari($query, string $kata)
    {
        return $query->where(function ($q) use ($kata) {
            $q->where('title', 'like', "%{$kata}%")
                ->orWhere('author', 'like', "%{$kata}%")
                ->orWhere('isbn_issn', 'like', "%{$kata}%")
                ->orWhere('subject', 'like', "%{$kata}%");
        });
    }

    public function scopePopuler($query)
    {
        return $query->withCount('peminjaman')
            ->orderByDesc('peminjaman_count');
    }

    // Helpers
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
}