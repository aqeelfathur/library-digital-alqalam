<x-layouts.publik :title="$berita->title">

    {{-- BREADCRUMB --}}
    <div class="pt-16 bg-emerald-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex items-center gap-2 text-xs text-white">
                <a href="{{ route('beranda') }}" class="hover:text-emerald-200 transition-colors">Beranda</a>
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('berita.index') }}" class="hover:text-emerald-200 transition-colors">Berita</a>
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="font-medium truncate">{{ $berita->title }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="mb-6">
            <a href="{{ route('berita.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Berita
            </a>
        </div>

        <article class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
            @if($berita->thumbnail)
                <div class="aspect-video overflow-hidden bg-stone-100">
                    <img src="{{ $berita->thumbnailUrl() }}" alt="{{ $berita->title }}" class="w-full h-full object-cover" />
                </div>
            @endif

            <div class="p-8 lg:p-12">
                <div class="mb-4">
                    <span class="text-xs text-emerald-700 font-medium bg-emerald-50 px-2 py-0.5 rounded-full">
                        Berita Perpustakaan
                    </span>
                </div>
                <h1 class="font-playfair text-3xl lg:text-4xl font-bold text-stone-800 mb-4 leading-tight">
                    {{ $berita->title }}
                </h1>
                <p class="text-stone-500 text-sm mb-8">
                    Diterbitkan {{ $berita->published_at->translatedFormat('d F Y, H:i') }} WIB
                </p>

                <div class="prose prose-stone max-w-none text-stone-700 leading-relaxed">
                    {!! nl2br(e($berita->content)) !!}
                </div>
            </div>
        </article>

        {{-- Berita Lainnya --}}
        @if($beritaLainnya->isNotEmpty())
            <div class="mt-12">
                <h2 class="font-playfair text-2xl font-bold text-stone-800 mb-6">Berita Lainnya</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($beritaLainnya as $lain)
                        <a href="{{ route('berita.show', $lain) }}"
                           class="group bg-white rounded-xl border border-stone-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="aspect-video overflow-hidden bg-stone-100">
                                <img src="{{ $lain->thumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-stone-400 mb-1">{{ $lain->published_at->translatedFormat('d M Y') }}</p>
                                <h3 class="font-semibold text-stone-800 text-sm leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2">
                                    {{ $lain->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</x-layouts.publik>
