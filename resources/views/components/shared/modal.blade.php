@props([
    'id'     => 'modal',
    'judul'  => '',
    'ukuran' => 'md',
])

@php
$lebarModal = match($ukuran) {
    'sm'  => 'max-w-md',
    'md'  => 'max-w-lg',
    'lg'  => 'max-w-2xl',
    'xl'  => 'max-w-4xl',
    default => 'max-w-lg',
};
@endphp

<div
    x-data="{ buka: false }"
    x-on:buka-modal-{{ $id }}.window="buka = true"
    x-on:tutup-modal-{{ $id }}.window="buka = false"
>
    {{-- Slot Pemicu --}}
    {{ $pemicu ?? '' }}

    {{-- Backdrop --}}
    <div
        x-show="buka"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="buka = false"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm"
        x-cloak
    ></div>

    {{-- Panel Modal --}}
    <div
        x-show="buka"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-cloak
    >
        <div class="bg-white rounded-2xl shadow-xl {{ $lebarModal }} w-full" @click.stop>
            {{-- Header --}}
            @if($judul)
                <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100">
                    <h3 class="font-semibold text-stone-800">{{ $judul }}</h3>
                    <button @click="buka = false"
                            class="p-1 text-stone-400 hover:text-stone-600 hover:bg-stone-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Konten --}}
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>