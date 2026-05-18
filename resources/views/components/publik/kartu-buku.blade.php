@props(['buku', 'ukuran' => 'normal'])

<div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-stone-100">
    {{-- Sampul --}}
    <div class="aspect-[3/4] overflow-hidden bg-stone-100">
        <img
            src="{{ $buku->sampulUrl() }}"
            alt="{{ $buku->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
        />
    </div>

    {{-- Konten --}}
    <div class="p-4">
        {{-- Kategori --}}
        <span class="inline-block text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full mb-2">
            {{ $buku->kategori->name ?? 'Umum' }}
        </span>

        {{-- Judul --}}
        <h3 class="font-semibold text-stone-800 text-sm leading-snug line-clamp-2 mb-1 group-hover:text-emerald-700 transition-colors">
            {{ $buku->title }}
        </h3>

        {{-- Penulis --}}
        <p class="text-xs text-stone-500 mb-3">{{ $buku->author }}</p>

        {{-- Status --}}
        <div class="flex items-center justify-between">
            <span @class([
                'text-xs font-medium px-2 py-0.5 rounded-full',
                'bg-green-100 text-green-700'  => $buku->status === 'tersedia',
                'bg-yellow-100 text-yellow-700' => $buku->status === 'dipinjam',
                'bg-orange-100 text-orange-700' => $buku->status === 'maintenance',
                'bg-red-100 text-red-700'       => $buku->status === 'hilang',
            ])>
                {{ $buku->labelStatus() }}
            </span>
        </div>
    </div>
</div>