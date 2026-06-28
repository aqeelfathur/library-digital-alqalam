<x-layouts.pustakawan title="Detail Buku">

    <div class="mb-6">
        <a href="{{ route('pustakawan.buku.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-emerald-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Koleksi Buku
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
            <div class="md:col-span-1 bg-stone-100 flex items-center justify-center p-8">
                <img src="{{ $buku->sampulUrl() }}" alt="{{ $buku->title }}" class="max-h-96 w-full object-contain rounded-lg shadow-md" />
            </div>

            <div class="md:col-span-2 p-8">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div>
                        <span class="inline-block text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3">
                            {{ $buku->kategori->name ?? 'Umum' }}
                        </span>
                        <h1 class="font-playfair text-3xl font-bold text-stone-800 mb-2 leading-tight">{{ $buku->title }}</h1>
                        <p class="text-stone-600 text-lg">{{ $buku->author }}</p>
                    </div>
                    <a href="{{ route('pustakawan.buku.edit', $buku) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
                        Edit Buku
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(array_filter([
                        'Penerbit' => $buku->publisher,
                        'Tahun Terbit' => $buku->publication_year,
                        'Bahasa' => $buku->language,
                        'ISBN / ISSN' => $buku->isbn_issn,
                        'Nomor Panggil' => $buku->call_number,
                        'Klasifikasi' => $buku->classification,
                        'Subyek' => $buku->subject,
                        'Lokasi' => $buku->location,
                        'Tipe Koleksi' => $buku->labelTipeKoleksi(),
                        'Status' => $buku->labelStatus(),
                    ]) as $label => $nilai)
                        <div class="text-sm">
                            <span class="text-stone-500">{{ $label }}</span>
                            <p class="font-medium text-stone-800 mt-0.5">{{ $nilai }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-buku.ulasan :buku="$buku" />

</x-layouts.pustakawan>
