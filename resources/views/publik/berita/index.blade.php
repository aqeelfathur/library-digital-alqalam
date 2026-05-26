<x-layouts.publik title="Berita — Perpustakaan Al-Qalam">

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

        {{-- Cari --}}
        <form method="GET" action="{{ route('berita.index') }}" class="mb-8 flex gap-3">
            <input name="cari" value="{{ request('cari') }}" type="text"
                   placeholder="Cari berita..."
                   class="flex-1 px-4 py-2.5 border border-stone-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" />
            <button type="submit" class="px-5 py-2.5 bg-emerald-700 text-white text-sm rounded-lg hover:bg-emerald-800 transition-colors">Cari</button>
            @if(request('cari'))
                <a href="{{ route('berita.index') }}" class="px-5 py-2.5 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">Reset</a>
            @endif
        </form>

        @if($berita->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($berita as $item)
                    <a href="{{ route('berita.show', $item) }}" class="group block">
                        <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden hover:shadow-md transition-shadow h-full">
                            <div class="aspect-video overflow-hidden bg-stone-100">
                                <img src="{{ $item->thumbnailUrl() }}" alt="{{ $item->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-stone-400 mb-2">
                                    {{ $item->published_at->translatedFormat('d F Y') }}
                                </p>
                                <h3 class="font-semibold text-stone-800 leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2">
                                    {{ $item->title }}
                                </h3>
                                <p class="text-sm text-stone-500 mt-2 line-clamp-2">
                                    {{ Str::limit(strip_tags($item->content), 100) }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            {{ $berita->links() }}
        @else
            <x-shared.kosong pesan="Belum ada berita yang diterbitkan." />
        @endif
    </div>

</x-layouts.publik>