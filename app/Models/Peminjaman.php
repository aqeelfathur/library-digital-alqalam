<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'borrowings';

    protected $fillable = [
        'user_id',
        'book_id',
        'processed_by',
        'status',
        'borrowed_at',
        'due_date',
        'returned_at',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'due_date'    => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    // Relationships
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'book_id');
    }

    public function pustakawan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->whereNull('returned_at')
            ->whereIn('status', ['pending', 'approved', 'borrowed']);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'borrowed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('returned_at');
    }

    // Helpers
    public function sudahDikembalikan(): bool
    {
        return $this->returned_at !== null;
    }

    public function terlambat(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->sudahDikembalikan();
    }

    public function sisaHari(): int
    {
        if (!$this->due_date || $this->sudahDikembalikan()) {
            return 0;
        }
        return (int) now()->diffInDays($this->due_date, false);
    }

    public function labelStatus(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            'late'     => 'Terlambat',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    public function warnaStatus(): string
    {
        return match ($this->status) {
            'pending'  => 'yellow',
            'approved' => 'blue',
            'borrowed' => 'green',
            'returned' => 'gray',
            'late'     => 'red',
            'rejected' => 'red',
            default    => 'gray',
        };
    }
}