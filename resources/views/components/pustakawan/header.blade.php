@props(['title' => 'Dasbor'])

<header class="bg-white border-b border-stone-200 px-6 py-4 flex items-center gap-4">
    <button
        @click="sidebarTerbuka = !sidebarTerbuka"
        class="p-2 rounded-lg text-stone-500 hover:bg-stone-100 transition-colors"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <div class="flex-1">
        <h1 class="text-lg font-semibold text-stone-800">{{ $title }}</h1>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('beranda') }}" target="_blank"
           class="text-sm text-stone-500 hover:text-emerald-700 transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Website
        </a>
    </div>
</header>