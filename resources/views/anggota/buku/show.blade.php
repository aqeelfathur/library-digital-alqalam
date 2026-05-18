<x-layouts.anggota title="{{ $buku->title }}">

    <div class="mb-6">
        <a href="{{ route('anggota.buku.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-emerald-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Buku
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">

            {{-- Sampul --}}
            <div class="md:col-span-1 bg-stone-100 flex items-center justify-center p-8">
                <img
                    src="{{ $buku->sampulUrl() }}"
                    alt="{{ $buku->title }}"
                    class="max-h-96 w-full object-contain rounded-lg shadow-md"
                />
            </div>

            {{-- Detail --}}
            <div class="md:col-span-2 p-8">
                <div class="mb-6">
                    <span class="inline-block text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3">
                        {{ $buku->kategori->name ?? 'Umum' }}
                    </span>
                    <h1 class="font-playfair text-3xl font-bold text-stone-800 mb-2 leading-tight">
                        {{ $buku->title }}
                    </h1>
                    @if($buku->series_title)
                        <p class="text-stone-500 text-sm mb-1">Seri: {{ $buku->series_title }}</p>
                    @endif
                    <p class="text-stone-600 text-lg">{{ $buku->author }}</p>
                </div>

                {{-- Status & Aksi Pinjam --}}
                <div class="mb-6 p-4 rounded-xl border @if($buku->isTersedia()) bg-green-50 border-green-200 @else bg-stone-50 border-stone-200 @endif">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-2">
                            <span @class([
                                'text-sm font-semibold px-3 py-1 rounded-full',
                                'bg-green-100 text-green-700'   => $buku->status === 'tersedia',
                                'bg-yellow-100 text-yellow-700' => $buku->status === 'dipinjam',
                                'bg-orange-100 text-orange-700' => $buku->status === 'maintenance',
                                'bg-red-100 text-red-700'       => $buku->status === 'hilang',
                            ])>
                                {{ $buku->labelStatus() }}
                            </span>
                        </div>

                        @if($buku->isTersedia())
                            @if($sedangMeminjam)
                                <div class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-2">
                                    Kembalikan buku yang sedang dipinjam terlebih dahulu.
                                </div>
                            @else
                                <form method="POST" action="{{ route('anggota.peminjaman.ajukan', $buku) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 text-white font-semibold rounded-lg hover:bg-emerald-800 transition-colors text-sm"
                                            onclick="return confirm('Ajukan peminjaman buku \'{{ addslashes($buku->title) }}\'?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                                        </svg>
                                        Pinjam Buku Ini
                                    </button>
                                </form>
                            @endif
                        @else
                            <p class="text-sm text-stone-500">Buku ini sedang tidak tersedia untuk dipinjam.</p>
                        @endif
                    </div>
                </div>

                {{-- Informasi Bibliografi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                    $detail = array_filter([
                        'Penerbit'        => $buku->publisher,
                        'Edisi'           => $buku->edition,
                        'Bahasa'          => $buku->language,
                        'ISBN / ISSN'     => $buku->isbn_issn,
                        'Nomor Panggil'   => $buku->call_number,
                        'Klasifikasi'     => $buku->classification,
                        'Deskripsi Fisik' => $buku->physical_description,
                        'Subyek'          => $buku->subject,
                        'Jenis Konten'    => $buku->content_type,
                        'Jenis Media'     => $buku->media_type,
                        'Jenis Carrier'   => $buku->carrier_type,
                    ]);
                    @endphp
                    @foreach($detail as $label => $nilai)
                        <div class="text-sm">
                            <span class="text-stone-500">{{ $label }}</span>
                            <p class="font-medium text-stone-800 mt-0.5">{{ $nilai }}</p>
                        </div>
                    @endforeach
                </div>

                @if($buku->specific_detail_info)
                    <div class="mt-4 p-4 bg-stone-50 rounded-lg">
                        <p class="text-xs font-medium text-stone-500 uppercase tracking-wider mb-1">Catatan</p>
                        <p class="text-sm text-stone-700">{{ $buku->specific_detail_info }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.anggota>