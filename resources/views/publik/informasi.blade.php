<x-layouts.publik title="Informasi — Perpustakaan Al-Qalam">

    <div class="bg-emerald-800 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="font-playfair text-4xl font-bold text-white mb-3">Informasi Perpustakaan</h1>
            <p class="text-emerald-200">Temukan semua informasi tentang layanan dan koleksi perpustakaan kami.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kontak & Lokasi --}}
            <div class="lg:col-span-1 space-y-5">
                <div class="bg-white rounded-xl border border-stone-100 shadow-sm p-6">
                    <h2 class="font-semibold text-stone-800 mb-4">Kontak & Lokasi</h2>
                    <ul class="space-y-4">
                        @if($informasi->address)
                            <li class="flex gap-3 text-sm">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-stone-600">{{ $informasi->address }}</span>
                            </li>
                        @endif
                        @if($informasi->phone)
                            <li class="flex gap-3 text-sm">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-stone-600">{{ $informasi->phone }}</span>
                            </li>
                        @endif
                        @if($informasi->email)
                            <li class="flex gap-3 text-sm">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-stone-600">{{ $informasi->email }}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="bg-white rounded-xl border border-stone-100 shadow-sm p-6">
                    <h2 class="font-semibold text-stone-800 mb-3">Jam Operasional</h2>
                    <p class="text-sm text-stone-600 leading-relaxed whitespace-pre-line">{{ $informasi->operational_hours ?? '-' }}</p>
                </div>

                <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-6">
                    <h2 class="font-semibold text-stone-800 mb-3">Statistik Koleksi</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-emerald-700">{{ $totalBuku }}</p>
                            <p class="text-xs text-stone-500">Total Buku</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-emerald-700">{{ $totalKategori }}</p>
                            <p class="text-xs text-stone-500">Kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Keanggotaan --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-stone-100 shadow-sm p-6">
                    <h2 class="font-semibold text-stone-800 mb-4">Informasi Keanggotaan</h2>
                    <div class="prose prose-sm prose-stone max-w-none">
                        <p class="text-stone-600 leading-relaxed whitespace-pre-line">
                            {{ $informasi->membership_information ?? 'Hubungi pustakawan untuk informasi keanggotaan.' }}
                        </p>
                    </div>
                </div>

                @if($informasi->maps_embed_url)
                    <div class="mt-5 bg-white rounded-xl border border-stone-100 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-stone-100">
                            <h2 class="font-semibold text-stone-800">Lokasi di Peta</h2>
                        </div>
                        <div class="h-72">
                            {!! $informasi->maps_embed_url !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.publik>