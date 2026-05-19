@props(['buku'])

<div
    class="bg-white rounded-xl shadow-sm border border-stone-100 hover:shadow-md hover:border-emerald-100 transition-all duration-200 overflow-hidden"
    x-data="{ detailTerbuka: false }"
>
    <div class="flex gap-4 p-4">

        {{-- Sampul Buku --}}
        <div class="flex-shrink-0">
            <img
                src="{{ $buku->sampulUrl() }}"
                alt="{{ $buku->title }}"
                class="w-16 h-22 object-cover rounded-lg shadow-sm border border-stone-100"
                loading="lazy"
                style="min-height: 88px; height: 88px;"
            />
        </div>

        {{-- Informasi Utama --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">

                {{-- Detail Buku --}}
                <div class="flex-1 min-w-0">
                    {{-- Kategori & Tipe --}}
                    <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                            {{ $buku->kategori->name ?? 'Umum' }}
                        </span>
                        @if($buku->collection_type)
                            <span class="text-xs font-medium text-stone-500 bg-stone-100 px-2 py-0.5 rounded-full">
                                {{ $buku->labelTipeKoleksi() }}
                            </span>
                        @endif
                    </div>

                    {{-- Judul --}}
                    <h3 class="font-semibold text-stone-800 text-sm leading-snug mb-1 line-clamp-2">
                        {{ $buku->title }}
                    </h3>

                    {{-- Penulis --}}
                    <div class="flex flex-wrap items-center gap-1 mb-2">
                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-xs text-stone-500">{{ $buku->author }}</span>
                    </div>

                    {{-- Meta Info --}}
                    <div class="flex flex-wrap items-center gap-3 text-xs text-stone-400">
                        @if($buku->publisher)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $buku->publisher }}
                            </span>
                        @endif
                        @if($buku->publication_year)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $buku->publication_year }}
                            </span>
                        @endif
                        @if($buku->call_number)
                            <span class="font-mono">{{ $buku->call_number }}</span>
                        @endif
                    </div>
                </div>

                {{-- Aksi & Ketersediaan --}}
                <div class="flex sm:flex-col items-center sm:items-end gap-2 flex-shrink-0">
                    {{-- Status Ketersediaan --}}
                    <span @class([
                        'inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full',
                        'bg-green-100 text-green-700'   => $buku->status === 'tersedia',
                        'bg-yellow-100 text-yellow-700' => $buku->status === 'dipinjam',
                        'bg-orange-100 text-orange-700' => $buku->status === 'maintenance',
                        'bg-red-100 text-red-700'       => $buku->status === 'hilang',
                    ])>
                        <span @class([
                            'w-1.5 h-1.5 rounded-full',
                            'bg-green-500'  => $buku->status === 'tersedia',
                            'bg-yellow-500' => $buku->status === 'dipinjam',
                            'bg-orange-500' => $buku->status === 'maintenance',
                            'bg-red-500'    => $buku->status === 'hilang',
                        ])></span>
                        {{ $buku->labelStatus() }}
                    </span>

                    {{-- Tombol Detail --}}
                    @auth
                        @if(auth()->user()->isAnggota())
                            <a href="{{ route('anggota.buku.show', $buku) }}"
                               class="inline-flex items-center gap-1 text-xs px-3 py-1.5 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-colors font-medium whitespace-nowrap">
                                Detail
                            </a>
                        @endif
                    @else
                        <a href="{{ route('anggota.login') }}"
                           class="inline-flex items-center gap-1 text-xs px-3 py-1.5 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors font-medium whitespace-nowrap">
                            Detail
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Deskripsi singkat --}}
            @if($buku->description || $buku->subject)
                <p class="text-xs text-stone-500 mt-2 line-clamp-1">
                    {{ $buku->description ?? $buku->subject }}
                </p>
            @endif
        </div>
    </div>

    {{-- Expandable Detail Area --}}
    <div
        x-show="detailTerbuka"
        x-collapse
        class="border-t border-stone-100 bg-stone-50 px-4 py-3"
    >
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
            @foreach(array_filter([
                'ISBN/ISSN'      => $buku->isbn_issn,
                'Klasifikasi'    => $buku->classification,
                'Edisi'          => $buku->edition,
                'Bahasa'         => $buku->language,
                'Deskripsi Fisik'=> $buku->physical_description,
                'Jenis Konten'   => $buku->content_type,
                'Jenis Media'    => $buku->media_type,
                'Lokasi'         => $buku->location,
                'GMD'            => $buku->gmd_type,
            ]) as $label => $nilai)
                <div>
                    <span class="text-stone-400 block">{{ $label }}</span>
                    <span class="text-stone-700 font-medium">{{ $nilai }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Toggle Detail --}}
    <button
        @click="detailTerbuka = !detailTerbuka"
        class="w-full flex items-center justify-center gap-1 py-2 text-xs text-stone-400 hover:text-emerald-700 hover:bg-stone-50 transition-colors border-t border-stone-100"
    >
        <span x-text="detailTerbuka ? 'Sembunyikan Detail' : 'Lihat Detail Lengkap'"></span>
        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="detailTerbuka ? 'rotate-180' : ''"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
</div>