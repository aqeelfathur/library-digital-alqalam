<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image_url',
        'status',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function peminjamanDiproses(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'processed_by');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    // Scopes
    public function scopeAnggota($query)
    {
        return $query->where('role', 'anggota');
    }

    public function scopePustakawan($query)
    {
        return $query->where('role', 'pustakawan');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Helpers
    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function isPustakawan(): bool
    {
        return $this->role === 'pustakawan';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function peminjamanAktif(): ?Peminjaman
    {
        return $this->peminjaman()
            ->whereNull('returned_at')
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->first();
    }

    public function sedangMeminjam(): bool
    {
        return $this->peminjamanAktif() !== null;
    }

    public function fotoUrl(): string
    {
        return $this->image_url
            ? asset('storage/' . $this->image_url)
            : asset('images/default-avatar.png');
    }
}