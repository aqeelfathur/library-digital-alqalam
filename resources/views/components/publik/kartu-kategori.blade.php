@props(['kategori', 'ikon' => null])

@php
$ikonKategori = [
    'Karya Umum' => asset('images/ikon-kategori-alqalam/karyaumum.png'),
    'Filsafat' => asset('images/ikon-kategori-alqalam/filsafat.png'),
    'Agama' => asset('images/ikon-kategori-alqalam/agama.png'),
    'Ilmu-ilmu Sosial' => asset('images/ikon-kategori-alqalam/ilmusosial.png'),
    'Bahasa' => asset('images/ikon-kategori-alqalam/bahasa.png'),
    'Ilmu-ilmu Murni' => asset('images/ikon-kategori-alqalam/ilmumurni.png'),
    'Ilmu-ilmu Terapan' => asset('images/ikon-kategori-alqalam/ilmuterapan.png'),
    'Kesenian, Hiburan, dan Olahraga' => asset('images/ikon-kategori-alqalam/kesenianhiburanolahraga.png'),
    'Kesusastraan' => asset('images/ikon-kategori-alqalam/kesusastraan.png'),
    'Geografi dan Sejarah' => asset('images/ikon-kategori-alqalam/geografisejarah.png'),
];
$pathIkon = $ikonKategori[$kategori->name]
    ?? null;
@endphp

<div class="group bg-white rounded-xl p-5 shadow-sm hover:shadow-md border border-stone-100 hover:border-emerald-200 transition-all duration-300 cursor-pointer text-center">
    <div class="w-12 h-12 bg-emerald-50 group-hover:bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors">
        <img 
            src="{{ $pathIkon }}" 
            class="w-12 h-12 mx-auto mb-3"
        />
    </div>
    <h3 class="font-semibold text-stone-800 text-xs mb-1">{{ $kategori->name }}</h3>
    <p class="text-xs text-stone-500">{{ $kategori->buku_count ?? 0 }} koleksi</p>
</div>