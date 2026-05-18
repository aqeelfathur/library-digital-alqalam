<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'slug'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->name);
            }
        });
    }

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}