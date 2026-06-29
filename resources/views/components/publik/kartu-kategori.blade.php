@props(['kategori'])

@php
// Dipetakan berdasarkan SLUG (stabil) -> file ikon yang sudah ada.
$ikonKategori = [
    'karya-umum'         => 'karyaumum.png',
    'filsafat-psikologi' => 'filsafat.png',
    'agama'              => 'agama.png',
    'ilmu-sosial'        => 'ilmusosial.png',
    'bahasa'             => 'bahasa.png',
    'sains'              => 'ilmumurni.png',
    'teknologi'          => 'ilmuterapan.png',
    'seni-rekreasi'      => 'kesenianhiburanolahraga.png',
    'sastra'             => 'kesusastraan.png',
    'sejarah-geografi'   => 'geografisejarah.png',
];

$file     = $ikonKategori[$kategori->slug] ?? null;
$pathIkon = $file
    ? asset('images/ikon-kategori-alqalam/' . $file)
    : asset('images/default-book.png'); // fallback (mis. kategori "Lainnya")
@endphp

<div class="group bg-white rounded-xl p-5 shadow-sm hover:shadow-md border border-stone-100 hover:border-emerald-200 transition-all duration-300 cursor-pointer text-center">
    <div class="w-12 h-12 bg-emerald-50 group-hover:bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors">
        <img
            src="{{ $pathIkon }}"
            alt="Ikon {{ $kategori->name }}"
            class="w-8 h-8 object-contain"
        />
    </div>
    <h3 class="font-semibold text-stone-800 text-xs mb-1">{{ $kategori->name }}</h3>
    <p class="text-xs text-stone-500">{{ $kategori->buku_count ?? 0 }} koleksi</p>
</div>