@props(['filter'])

<div class="bg-white rounded-xl border border-stone-100 shadow-sm p-12 text-center">
    <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-5">
        <svg class="w-10 h-10 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>

    <h3 class="font-playfair text-xl font-semibold text-stone-700 mb-2">Koleksi Tidak Ditemukan</h3>

    @if(filled($filter['search']))
        <p class="text-stone-500 text-sm mb-1">
            Tidak ada hasil untuk kata kunci
            <span class="font-semibold text-stone-700">"{{ $filter['search'] }}"</span>
        </p>
    @else
        <p class="text-stone-500 text-sm mb-1">
            Tidak ada koleksi yang sesuai dengan filter yang diterapkan.
        </p>
    @endif

    <p class="text-stone-400 text-xs mb-6">
        Coba ubah kata kunci atau kurangi filter pencarian Anda.
    </p>

    <div class="flex flex-wrap gap-2 justify-center">
        <a href="{{ route('explore') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Lihat Semua Koleksi
        </a>
        <a href="{{ route('beranda') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-stone-300 text-stone-600 text-sm rounded-lg hover:bg-stone-50 transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</div>