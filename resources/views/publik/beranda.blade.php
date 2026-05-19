{{-- resources/views/publik/beranda.blade.php --}}

<x-layouts.publik title="Beranda — Perpustakaan Digital Al-Qalam">

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 min-h-[520px] flex items-center">
        <div class="absolute inset-0 opacity-10"
             style="background-image: url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23ffffff\" fill-opacity=\"0.4\"><path d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/></g></g></svg>');">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="text-center text-white mb-10">
                <span class="inline-block text-xs font-semibold tracking-widest uppercase text-emerald-300 mb-4">
                    Perpustakaan Digital Sekolah
                </span>
                <h1 class="font-playfair text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
                    Gerbang Ilmu <em class="text-emerald-300 not-italic">Al-Qalam</em>
                </h1>
                <p class="text-emerald-100 text-lg max-w-xl mx-auto mb-8">
                    Temukan ribuan koleksi buku pilihan. Pinjam, baca, dan kembangkan pengetahuan Anda.
                </p>
            </div>

            {{-- Search Bar Utama --}}
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('explore') }}" method="GET">
                    <div class="flex gap-0 bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="flex-1 flex items-center gap-3 px-5">
                            <svg class="w-5 h-5 text-stone-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                name="search"
                                type="text"
                                placeholder="Masukkan kata kunci untuk mencari koleksi..."
                                class="flex-1 py-4 text-stone-800 text-sm focus:outline-none placeholder:text-stone-400 bg-transparent"
                                autocomplete="off"
                            />
                        </div>
                        <button type="submit"
                                class="px-7 py-4 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-sm transition-colors flex-shrink-0">
                            Cari
                        </button>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center justify-center gap-2 text-xs text-emerald-300">
                        <span>Cari berdasarkan:</span>
                        @foreach(['Judul', 'Pengarang', 'Subjek', 'ISBN', 'Penerbit'] as $hint)
                            <span class="bg-white/10 px-2 py-0.5 rounded-full">{{ $hint }}</span>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </section>
    {{-- ↑ HERO: tag </section> ada di sini, bukan di tengah --}}

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

    {{-- KOLEKSI TERBARU --}}
    <section class="py-16 bg-white">
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
                    {!! $informasi->maps_embed_url !!}
                </div>
            </div>
        </section>
    @endif

</x-layouts.publik>