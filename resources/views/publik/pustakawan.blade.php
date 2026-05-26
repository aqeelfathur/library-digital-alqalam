<x-layouts.publik title="Pustakawan — Perpustakaan Al-Qalam">

    {{-- BREADCRUMB --}}
    <div class="pt-16 bg-emerald-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-white">
                <a href="{{ route('beranda') }}" class="hover:text-emerald-700 transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white font-medium">{{ $judulHalaman }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($pustakawan->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($pustakawan as $p)
                    <div class="bg-white rounded-xl shadow-sm border border-stone-100 p-6 flex items-center gap-5">
                        <img src="{{ $p->fotoUrl() }}"
                             alt="{{ $p->name }}"
                             class="w-20 h-20 rounded-full object-cover flex-shrink-0 border-4 border-emerald-100" />
                        <div>
                            <h3 class="font-semibold text-stone-800 text-lg">{{ $p->name }}</h3>
                            <p class="text-sm text-emerald-700 font-medium mb-2">Pustakawan</p>
                            <p class="text-sm text-stone-500">{{ $p->email }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-shared.kosong pesan="Tidak ada pustakawan yang ditampilkan saat ini." />
        @endif
    </div>

</x-layouts.publik>