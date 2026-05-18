<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = ['title', 'slug', 'content', 'thumbnail', 'published_at'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->title);
            }
        });
    }

    public function scopeTerbit($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeCari($query, string $kata)
    {
        return $query->where('title', 'like', "%{$kata}%");
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function thumbnailUrl(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('images/default-news.png');
    }
}