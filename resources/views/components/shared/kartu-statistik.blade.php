@props(['judul', 'nilai', 'ikon', 'warna' => 'emerald', 'keterangan' => null])

@php
$warnaKelas = [
    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => 'bg-emerald-100'],
    'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'icon' => 'bg-blue-100'],
    'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'icon' => 'bg-amber-100'],
    'rose'    => ['bg' => 'bg-rose-50',    'text' => 'text-rose-700',    'icon' => 'bg-rose-100'],
][$warna] ?? ['bg' => 'bg-stone-50', 'text' => 'text-stone-700', 'icon' => 'bg-stone-100'];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm font-medium text-stone-600">{{ $judul }}</p>
        <div class="{{ $warnaKelas['icon'] }} w-10 h-10 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 {{ $warnaKelas['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon }}" />
            </svg>
        </div>
    </div>
    <p class="text-3xl font-bold text-stone-800">{{ $nilai }}</p>
    @if($keterangan)
        <p class="text-xs text-stone-500 mt-1">{{ $keterangan }}</p>
    @endif
</div>