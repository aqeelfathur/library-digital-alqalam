{{-- resources/views/publik/beranda.blade.php --}}

<x-layouts.publik title="Beranda — Perpustakaan Digital Al-Qalam">

    {{-- HERO --}}
    <section 
        x-data="{
            current: 0,
            banners: [
                '{{ asset('images/smamda-banner1.png') }}',
                '{{ asset('images/smamda-banner2.png') }}'
            ]
        }"
        x-init="setInterval(() => current = (current + 1) % banners.length, 5000)"
        class="relative overflow-hidden min-h-[520px] flex items-center pt-16"
    >

        {{-- Background Images --}}
        <template x-for="(banner, index) in banners" :key="index">
            <div
                x-show="current === index"
                x-transition:enter="transition-opacity duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-1000 absolute inset-0"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0"
            >
                <img
                    :src="banner"
                    class="w-full h-full object-cover"
                    alt="Banner"
                >
            </div>
        </template>

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/50"></div>

        {{-- HERO CONTENT --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">

            <div class="text-center text-white mb-10">
                <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
                    Perpustakaan SMAMDA Surabaya
                </h1>

                <p class="text-white text-lg max-w-xl mx-auto mb-8">
                    Temukan ribuan koleksi buku pilihan. Pinjam, baca, dan kembangkan pengetahuan Anda.
                </p>
            </div>

            {{-- Search Bar --}}
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('explore') }}" method="GET">
                    <div class="flex bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="flex-1">
                            <input
                                name="search"
                                type="text"
                                placeholder="Masukkan kata kunci untuk mencari koleksi..."
                                class="w-full px-5 py-4 text-stone-800 text-sm focus:outline-none placeholder:text-stone-400"
                                autocomplete="off"
                            />
                        </div>

                        <button type="submit"
                                class="px-7 py-4 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm transition-colors flex-shrink-0">
                            Cari
                        </button>

                    </div>
                </form>
            </div>

        </div>
    </section>

    {{-- KATEGORI --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="font-playfair text-4xl font-bold text-stone-800 mb-2">Kategori Koleksi</h2>
                <p class="text-stone-500 text-sm">Temukan buku berdasarkan kategori yang Anda minati</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach($kategori->take(10) as $kat)
                    <a href="{{ route('explore', ['kategori' => $kat->slug]) }}">
                        <x-publik.kartu-kategori :kategori="$kat" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- KOLEKSI POPULER --}}
    <section class="py-16 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <h2 class="font-playfair text-3xl font-bold text-stone-800 mb-2">Koleksi Populer</h2>
                    <p class="text-stone-500 text-sm">Buku yang paling banyak dipinjam</p>
                </div>
                <a href="{{ route('explore', ['urutan' => 'populer']) }}"
                   class="text-sm text-emerald-700 font-medium hover:underline hidden sm:block">
                    Lihat semua
                </a>
            </div>
            @if($bukuPopuler->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
                    @foreach($bukuPopuler as $buku)
                        <x-publik.kartu-buku :buku="$buku" />
                    @endforeach
                </div>
            @else
                <x-shared.kosong pesan="Belum ada koleksi buku." />
            @endif
        </div>
    </section>

    {{-- REKOMENDASI BUKU --}}
    @if($bukuRekomendasi->isNotEmpty())
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-emerald-600 uppercase tracking-widest font-jakarta">Paling Banyak Dipinjam</span>
                    </div>
                    <h2 class="font-playfair text-3xl font-bold text-stone-800 mb-2">Rekomendasi Buku</h2>
                    <p class="text-stone-500 text-sm">Koleksi terpopuler pilihan anggota perpustakaan kami</p>
                </div>
            </div>

            {{-- Grid 6 Buku --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
                @foreach($bukuRekomendasi as $urutan => $buku)
                <div class="group relative flex flex-col">

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
                             loading="lazy">

                        {{-- Overlay hover --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/80 via-stone-900/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                            <span class="inline-flex items-center gap-1 text-white/90 text-xs font-jakarta font-medium">
                                <svg class="w-3 h-3 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                {{ $buku->slims_loan_count ?? 0 }}× dipinjam
                            </span>
                        </div>
                    </a>

                    {{-- Info Buku --}}
                    <div class="mt-3 flex-1 flex flex-col">
                        <h3 class="font-playfair text-xs sm:text-sm font-semibold text-stone-800 leading-snug line-clamp-2 group-hover:text-emerald-700 transition-colors">
                            <a href="{{ route('explore') }}?search={{ urlencode($buku->title) }}">
                                {{ $buku->title }}
                            </a>
                        </h3>
                        <p class="mt-1 text-xs text-stone-500 font-jakarta line-clamp-1">{{ $buku->author }}</p>
                        @if($buku->kategori)
                            <span class="mt-2 inline-block self-start text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-jakarta font-medium border border-emerald-100 leading-none">
                                {{ $buku->kategori->name }}
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>
    @endif

    {{-- KOLEKSI TERBARU --}}
    <section class="py-16 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-8 gap-4">
                <div>
                    <h2 class="font-playfair text-3xl font-bold text-stone-800 mb-2">Koleksi Terbaru</h2>
                    <p class="text-stone-500 text-sm">Penambahan terbaru dalam koleksi kami</p>
                </div>
                <a href="{{ route('explore', ['urutan' => 'terbaru']) }}"
                   class="text-sm text-emerald-700 font-medium hover:underline self-start sm:self-auto">
                    Lihat semua
                </a>
            </div>
            @if($bukuTerbaru->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
                    @foreach($bukuTerbaru as $buku)
                        <x-publik.kartu-buku :buku="$buku" />
                    @endforeach
                </div>
            @else
                <x-shared.kosong pesan="Belum ada koleksi terbaru." />
            @endif
        </div>
    </section>

    {{-- PENIKMAT KOLEKSI --}}
    @if($penikmatKoleksi->isNotEmpty())
        <section class="py-16 bg-emerald-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="font-playfair text-3xl font-bold text-white mb-2">Penikmat Koleksi Tahun Ini</h2>
                    <p class="text-emerald-300 text-sm">Anggota teraktif dalam meminjam koleksi tahun {{ date('Y') }}</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-2xl mx-auto">
                    @foreach($penikmatKoleksi as $i => $anggota)
                        <div class="text-center">
                            <div class="relative inline-block mb-3">
                                <img src="{{ $anggota->fotoUrl() }}"
                                     alt="{{ $anggota->name }}"
                                     class="w-20 h-20 rounded-full object-cover border-4 border-emerald-600 mx-auto" />
                                <span class="absolute -top-1 -right-1 w-6 h-6 bg-amber-400 text-stone-900 text-xs font-bold rounded-full flex items-center justify-center">
                                    {{ $i + 1 }}
                                </span>
                            </div>
                            <p class="font-semibold text-white">{{ $anggota->name }}</p>
                            <p class="text-sm text-emerald-300">{{ $anggota->peminjaman_count }} peminjaman</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PETA --}}
    @if($informasi->maps_embed_url)
        <section class="py-16 bg-stone-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="font-playfair text-3xl font-bold text-stone-800 mb-2">Lokasi Kami</h2>
                    <p class="text-stone-500 text-sm">Kunjungi perpustakaan kami secara langsung</p>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-lg h-80">
                    <iframe
                        src="{{ $informasi->maps_embed_url }}"
                        class="w-full h-full"
                        style="border:0;"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </section>
    @endif

</x-layouts.publik>
