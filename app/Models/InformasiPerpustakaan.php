<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiPerpustakaan extends Model
{
    protected $table = 'library_information';

    protected $fillable = [
        'address',
        'phone',
        'email',
        'operational_hours',
        'membership_information',
        'maps_embed_url',
    ];

    public static function ambil(): self
    {
        return static::firstOrCreate([], [
            'address'                => 'Alamat perpustakaan belum diatur',
            'phone'                  => '-',
            'email'                  => '-',
            'operational_hours'      => 'Senin - Jumat: 08.00 - 16.00',
            'membership_information' => 'Hubungi pustakawan untuk informasi keanggotaan',
            'maps_embed_url'         => '',
        ]);
    }
}