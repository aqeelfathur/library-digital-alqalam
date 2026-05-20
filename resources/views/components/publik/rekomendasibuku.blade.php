{{-- resources/views/publik/partials/rekomendasi-buku.blade.php --}}
{{-- Data: $bukuRekomendasi (Collection of Buku, max 6, sorted by peminjaman_count desc) --}}

@if($bukuRekomendasi->isNotEmpty())
<section class="py-16 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100">
                        {{-- Ikon api/trending --}}
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                        </svg>
                    </span>
                    <span class="text-sm font-semibold text-emerald-600 uppercase tracking-widest font-jakarta">Paling Banyak Dipinjam</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-stone-800 font-playfair leading-tight">
                    Rekomendasi <span class="text-emerald-600">Buku</span>
                </h2>
                <p class="mt-2 text-stone-500 font-jakarta text-sm sm:text-base max-w-md">
                    Koleksi terpopuler pilihan anggota — buku-buku yang paling banyak dipinjam di perpustakaan kami.
                </p>
            </div>
            <a href="{{ route('explore', ['urutan' => 'populer']) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 font-jakarta group shrink-0">
                Lihat Semua Koleksi
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        {{-- Grid Buku --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
            @foreach($bukuRekomendasi as $urutan => $buku)
            <div class="group relative flex flex-col" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">

                {{-- Badge Ranking --}}
                <div class="absolute -top-2 -left-2 z-10">
                    @if($urutan === 0)
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-white text-xs font-bold shadow-md font-jakarta">#1</span>
                    @elseif($urutan === 1)
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-stone-400 text-white text-xs font-bold shadow-md font-jakarta">#2</span>
                    @elseif($urutan === 2)
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white text-xs font-bold shadow-md font-jakarta">#3</span>
                    @else
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-stone-200 text-stone-600 text-xs font-bold shadow font-jakarta">#{{ $urutan + 1 }}</span>
                    @endif
                </div>

                {{-- Cover Buku --}}
                <a href="{{ route('explore') }}?search={{ urlencode($buku->title) }}"
                   class="block overflow-hidden rounded-xl shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1 bg-stone-200 aspect-[2/3] relative">

                    <img src="{{ $buku->sampulUrl() }}"
                         alt="Sampul {{ $buku->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder-buku.png') }}'">

                    {{-- Overlay hover --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-stone-900/80 via-stone-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                        <span class="inline-flex items-center gap-1 text-white/90 text-xs font-jakarta font-medium">
                            <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            {{ $buku->peminjaman_count ?? 0 }}x dipinjam
                        </span>
                    </div>
                </a>

                {{-- Info Buku --}}
                <div class="mt-3 flex-1 flex flex-col">
                    <h3 class="text-xs sm:text-sm font-semibold text-stone-800 font-playfair leading-snug line-clamp-2 group-hover:text-emerald-700 transition-colors">
                        <a href="{{ route('explore') }}?search={{ urlencode($buku->title) }}">
                            {{ $buku->title }}
                        </a>
                    </h3>
                    <p class="mt-1 text-xs text-stone-500 font-jakarta line-clamp-1">{{ $buku->author }}</p>

                    {{-- Kategori badge --}}
                    @if($buku->kategori)
                    <span class="mt-2 inline-block self-start text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-jakarta font-medium border border-emerald-100 leading-none">
                        {{ $buku->kategori->name }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- CTA Banner bawah --}}
        <div class="mt-12 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-700 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-lg shadow-emerald-200">
            <div class="text-center sm:text-left">
                <p class="text-emerald-100 text-sm font-jakarta">Temukan lebih banyak koleksi</p>
                <p class="text-white text-xl font-bold font-playfair mt-0.5">Jelajahi Semua Koleksi Perpustakaan</p>
            </div>
            <a href="{{ route('explore') }}"
               class="shrink-0 inline-flex items-center gap-2 bg-white text-emerald-700 font-semibold text-sm px-5 py-2.5 rounded-xl hover:bg-emerald-50 transition-colors font-jakarta shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                Explore Koleksi
            </a>
        </div>

    </div>
</section>
@endif