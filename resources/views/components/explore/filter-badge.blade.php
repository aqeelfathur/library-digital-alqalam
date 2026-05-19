@props(['label', 'nilai', 'kunci', 'filter'])

@php
$filterBaru = array_filter(array_merge($filter, [$kunci => '']));
@endphp

<a
    href="{{ route('explore', $filterBaru) }}"
    class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition-colors group"
    title="Hapus filter {{ $label }}"
>
    <span>{{ $label }}: {{ Str::limit($nilai, 20) }}</span>
    <svg class="w-3 h-3 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
</a>