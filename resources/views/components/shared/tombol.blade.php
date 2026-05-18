@props([
    'varian'  => 'primer',
    'ukuran'  => 'md',
    'tipe'    => 'button',
    'href'    => null,
    'ikon'    => null,
    'loading' => false,
])

@php
$varianKelas = match($varian) {
    'primer'   => 'bg-emerald-700 text-white hover:bg-emerald-800 focus:ring-emerald-500',
    'sekunder' => 'border border-stone-300 text-stone-700 hover:bg-stone-50 focus:ring-stone-300',
    'bahaya'   => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'hantu'    => 'text-emerald-700 hover:bg-emerald-50 focus:ring-emerald-500',
    default    => 'bg-emerald-700 text-white hover:bg-emerald-800 focus:ring-emerald-500',
};

$ukuranKelas = match($ukuran) {
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
    default => 'px-5 py-2.5 text-sm',
};

$kelasUmum = 'inline-flex items-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$kelasUmum $varianKelas $ukuranKelas"]) }}>
        @if($ikon)
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon }}" />
            </svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $tipe }}"
        {{ $attributes->merge(['class' => "$kelasUmum $varianKelas $ukuranKelas"]) }}
    >
        @if($loading)
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($ikon)
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon }}" />
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif