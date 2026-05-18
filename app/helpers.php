<?php

if (!function_exists('format_tanggal')) {
    function format_tanggal(?string $tanggal, string $format = 'd F Y'): string
    {
        if (!$tanggal) return '-';
        return \Carbon\Carbon::parse($tanggal)->translatedFormat($format);
    }
}

if (!function_exists('format_status_badge')) {
    function format_status_badge(string $status): array
    {
        return match ($status) {
            'pending'   => ['label' => 'Menunggu',     'class' => 'bg-yellow-100 text-yellow-700'],
            'approved'  => ['label' => 'Disetujui',    'class' => 'bg-blue-100 text-blue-700'],
            'borrowed'  => ['label' => 'Dipinjam',     'class' => 'bg-green-100 text-green-700'],
            'returned'  => ['label' => 'Dikembalikan', 'class' => 'bg-stone-100 text-stone-600'],
            'late'      => ['label' => 'Terlambat',    'class' => 'bg-red-100 text-red-700'],
            'rejected'  => ['label' => 'Ditolak',      'class' => 'bg-red-100 text-red-700'],
            'tersedia'  => ['label' => 'Tersedia',     'class' => 'bg-green-100 text-green-700'],
            'dipinjam'  => ['label' => 'Dipinjam',     'class' => 'bg-yellow-100 text-yellow-700'],
            'maintenance' => ['label' => 'Perawatan',  'class' => 'bg-orange-100 text-orange-700'],
            'hilang'    => ['label' => 'Hilang',       'class' => 'bg-red-100 text-red-700'],
            'aktif'     => ['label' => 'Aktif',        'class' => 'bg-green-100 text-green-700'],
            'nonaktif'  => ['label' => 'Nonaktif',     'class' => 'bg-stone-100 text-stone-600'],
            'suspended' => ['label' => 'Ditangguhkan', 'class' => 'bg-red-100 text-red-700'],
            default     => ['label' => ucfirst($status), 'class' => 'bg-stone-100 text-stone-600'],
        };
    }
}